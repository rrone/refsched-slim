<?php
namespace App\Action\Full;

use App\Action\AbstractView;
use Slim\Container;
use App\Action\SchedulerRepository;
use Slim\Http\Request;
use Slim\Http\Response;

class SchedFullView extends AbstractView
{
    private $description;
    private $games;

    public function __construct(Container $container, SchedulerRepository $schedulerRepository)
    {
        parent::__construct($container, $schedulerRepository);

        $this->justOpen = false;
        $this->description = 'No matches scheduled';
        $this->games = null;
    }

    public function handler(Request $request, Response $response)
    {
        $this->user = $request->getAttribute('user');
        $this->event = $request->getAttribute('event');
        $params = $request->getParams();

        $this->justOpen = array_key_exists('open', $params);
        $this->sortOn = array_key_exists('sort', $params) ? $params['sort'] : 'game_number';
        if(empty($this->sortOn)) {
            $this->sortOn = 'game_number';
        }
        $this->uri = $request->getUri();

        return null;
    }

    public function render(Response &$response)
    {
        $content = array(
            'view' => array(
                'admin' => $this->user->admin,
                'content' => $this->renderView(),
                'topmenu' => $this->menu('top'),
                'menu' => $this->menu,
                'title' => $this->page_title,
                'dates' => $this->dates,
                'location' => $this->location,
                'description' => $this->description,
            )
        );

        $this->view->render($response, 'sched.html.twig', $content);
    }

    protected function renderView()
    {
        $html = null;
        $this->menu = null;

        if (!empty($this->event)) {
            $projectKey = $this->event->projectKey;
            //refresh event data
            $this->event = $this->sr->getEvent($projectKey);

            if(!empty($this->event->infoLink)){
                $eventLink = $this->event->infoLink;
                $eventName = $this->event->name;
                $eventName = "<a href='$eventLink' target='_blank'>$eventName</a>";
            } else {
                $eventName = $this->event->name;
            }

            $this->page_title = $eventName;
            $this->dates = $this->event->dates;
            $this->location = $this->event->location;

            $show_medal_round = $this->sr->getMedalRound($projectKey);

            if($this->user->admin) {
                $this->games = $this->sr->getGames($projectKey, '%', true, $this->sortOn);
            } else {
                $this->games = $this->sr->getGames($projectKey, '%', $show_medal_round, $this->sortOn);
            }

            if (count($this->games)) {
                $this->description = $this->user->name;
                if($this->justOpen) {
                    $this->description .= ": Schedule with Open Slots";
                } else {
                    $this->description .= ": Full Schedule";
                }

                $has4th = $this->sr->numberOfReferees($projectKey) > 3;

                $html .= "<h3 class=\"center\">Green: Assignments covered (Boo-yah!) / Yellow: Open Slots / Red: Needs your attention / Grey: Not yours to cover<br><br>\n";
                $html .= "Green shading change indicates different start times</h3>\n";

                $html .= "<table class=\"sched-table\" width=\"100%\">\n";
                $html .= "<tr class=\"center\" bgcolor=\"$this->colorTitle\">";
                $html .= "<th><a href=" . $this->getUri('fullPath') . ">Match#</a></th>";
                $html .= "<th><a href=" . $this->getUri('fullPath','date') . ">Date</a></th>";
                $html .= "<th>Time</th>";
                $html .= "<th><a href=" . $this->getUri('fullPath','field') . ">Field</a></th>";
                $html .= "<th><a href=" . $this->getUri('fullPath','division') . ">Division</a></th>";
                $html .= "<th><a href=" . $this->getUri('fullPath','pool') . ">Pool</a></th>";
                $html .= "<th><a href=" . $this->getUri('fullPath','home') . ">Home</a></th>";
                $html .= "<th><a href=" . $this->getUri('fullPath','away') . ">Away</a></th>";
                $html .= "<th><a href=" . $this->getUri('fullPath','assignor') . ">Referee Team</a></th>";
                $html .= "<th>Referee</th>";
                $html .= "<th>AR1</th>";
                $html .= "<th>AR2</th>";
                if ($has4th) {
                    $html .= "<th>4th</th>";
                }
                $html .= "</tr>\n";

                $rowColor = $this->colorGroup1;
                $testtime = null;

                foreach ($this->games as $game) {
                    if (!$this->justOpen || ($this->justOpen && (empty($game->cr) || empty($game->ar1) || empty($game->ar2) || ($has4th && empty($game->r4th))))) {
                        $date = date('D, d M', strtotime($game->date));
                        $time = date('H:i', strtotime($game->time));

                        if (!$testtime) {
                            $testtime = $time;
                        } elseif (($testtime != $time && $game->assignor == $this->user->name) || ($testtime != $time && $this->user->admin && !empty($game->assignor))) {
                            $testtime = $time;
                            switch ($rowColor) {
                                case $this->colorGroup1:
                                    $rowColor = $this->colorGroup2;
                                    break;
                                default:
                                    $rowColor = $this->colorGroup1;
                            }
                        }

                        if ($game->assignor == $this->user->name) {
                            //no refs
                            if (empty($game->cr) && empty($game->ar1) && empty($game->ar2) && (!$has4th || $has4th && empty($game->r4th))) {
                                $html .= "<tr class=\"center\" bgcolor=\"$this->colorUnassigned\">";
                                //open AR  or 4th slots
                            } elseif (empty($game->ar1) || empty($game->ar2) || ($has4th && empty($game->r4th))) {
                                $html .= "<tr class=\"center\" bgcolor=\"$this->colorOpenSlots\">";
                                //match covered
                            } else {
                                $html .= "<tr class=\"center\" bgcolor=\"$rowColor\">";
                            }
                        } else {
                            $html .= "<tr class=\"center\" bgcolor=\"$this->colorLtGray\">";
                        }
                        if ($this->user->admin) {
                            //no assignor
                            if (empty($game->assignor)) {
                                $html .= "<tr class=\"center\" bgcolor=\"$this->colorUnassigned\">";
                                //my open slots
                            } elseif ($game->assignor == $this->user->name && empty($game->cr) && empty($game->ar1) && empty($game->ar2) && (!$has4th || $has4th && empty($game->r4th))) {
                                $html .= "<tr class=\"center\" bgcolor=\"$this->colorUnassigned\">";
                                //assigned open slots
                            } elseif (empty($game->cr) || empty($game->ar1) || empty($game->ar2) || ($has4th && empty($game->r4th))) {
                                $html .= "<tr class=\"center\" bgcolor=\"$this->colorOpenSlots\">";
                                //match covered
                            } else {
                                $html .= "<tr class=\"center\" bgcolor=\"$rowColor\">";
                            }
                        }

                        $html .= "<td>$game->game_number</td>";
                        $html .= "<td>$date</td>";
                        $html .= "<td>$time</td>";
                        if (is_null($this->event->field_map)) {
                            $html .= "<td>$game->field</td>";
                        } else {
                            $html .= "<td><a href='".$this->event->field_map."' target='_blank'>$game->field</a></td>";
                        }
                        $html .= "<td>$game->division</td>";
                        $html .= "<td>$game->pool</td>";
                        $html .= "<td>$game->home</td>";
                        $html .= "<td>$game->away</td>";
                        $html .= "<td>$game->assignor</td>";
                        $html .= "<td>$game->cr</td>";
                        $html .= "<td>$game->ar1</td>";
                        $html .= "<td>$game->ar2</td>";
                        if ($has4th) {
                            $html .= "<td>$game->r4th</td>";
                        }
                        $html .= "</tr>\n";
                    }
                }

                $html .= "</table>\n";
            }

            $this->menu = count($this->games) ? $this->menu('bottom') : null ;
        }

        return $html;

    }

    private function menu($pos = 'top')
    {
        $html = null;

        $html .= "<h3 class=\"center h3-btn\">";

        if ($pos == 'bottom') {
            $html .= "<a  href=" . $this->getBaseURL('fullXlsPath') . " class=\"btn btn-primary btn-xs export right\" style=\"margin-right: 0\">Export to Excel<i class=\"icon-white icon-circle-arrow-down\"></i></a>";
            $html .= "<div class='clear-fix'></div>";
        }

        $html .= "<a  href=" . $this->getBaseURL('greetPath') . ">Home</a>&nbsp;-&nbsp;";
        if ($this->justOpen) {
            $html .= "<a  href=" . $this->getBaseURL('fullPath') . ">View full schedule</a>&nbsp;-&nbsp;";
        } else {
            $html .= "<a href=" . $this->getBaseURL('fullPath') . "?open>View schedule with open slots</a>&nbsp;-&nbsp;";
        }
        if ($this->user->admin) {
            if(!$this->event->archived) {
                $html .= "<a href=".$this->getBaseURL('editGamePath').">Edit matches</a>&nbsp;-&nbsp;";
            }            $html .= "<a  href=" . $this->getBaseURL('schedPath') . ">View Assignors</a>&nbsp;-&nbsp;";
            $html .= "<a  href=" . $this->getBaseURL('masterPath') . ">Select Assignors</a>&nbsp;-&nbsp;";
            $html .= "<a  href=" . $this->getBaseURL('refsPath') . ">Edit Referee Assignments</a>&nbsp;-&nbsp;";
        } else {
            $html .= "<a  href=" . $this->getBaseURL('schedPath') . ">Go to " . $this->user->name . " schedule</a>&nbsp;-&nbsp;";
            $html .= "<a  href=" . $this->getBaseURL('refsPath') . ">Edit " . $this->user->name . " referees</a>&nbsp;-&nbsp;";
        }

        $html .= "<a  href=" . $this->getBaseURL('endPath') . ">Log off</a><br>";

        if ($pos == 'top' and count($this->games)) {
            $html .= "<a  href=" . $this->getBaseURL('fullXlsPath') . " class=\"btn btn-primary btn-xs export right\" style=\"margin-right: 0\">Export to Excel<i class=\"icon-white icon-circle-arrow-down\"></i></a>";
            $html .= "<div class='clear-fix'></div>";
        }

        $html .= "</h3>\n";

        return $html;
    }

}