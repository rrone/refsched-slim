<?php
namespace App\Action\Full;

use App\Action\AbstractView;
use Slim\Container;
use App\Action\SchedulerRepository;
use Slim\Http\Request;
use Slim\Http\Response;

class SchedFullView extends AbstractView
{
    private $justOpen;

    public function __construct(Container $container, SchedulerRepository $schedulerRepository)
    {
        parent::__construct($container, $schedulerRepository);

        $this->justOpen = false;
    }
    public function handler(Request $request, Response $response)
    {
        $this->user = $request->getAttribute('user');
        $this->event = $request->getAttribute('event');
        $this->justOpen = array_key_exists('open', $request->getParams());

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
                'description' => "Full Schedule"
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

            $this->page_title = $this->event->name;
            $this->dates = $this->event->dates;
            $this->location = $this->event->location;

            if($this->user->admin) {
                $games = $this->sr->getGames($projectKey, '%', true);
            } else {
                $games = $this->sr->getGames($projectKey);
            }

            $has4th = $this->sr->numberOfReferees($projectKey) > 3;

            $html .= "<h3 class=\"center\">Green: Assignments covered (Yah!) / Yellow: Open Slots / Red: Needs your attention / Grey: Not yours to cover<br><br>\n";
            $html .= "Green shading change indicates different start times</h3>\n";

            $html .= "<table class=\"sched-table\" width=\"100%\">\n";
            $html .= "<tr class=\"center\" bgcolor=\"$this->colorTitle\">";
            $html .= "<th>Game No.</th>";
            $html .= "<th>Date</th>";
            $html .= "<th>Time</th>";
            $html .= "<th>Field</th>";
            $html .= "<th>Division</th>";
            $html .= "<th>Pool</th>";
            $html .= "<th>Home</th>";
            $html .= "<th>Away</th>";
            $html .= "<th>Referee Team</th>";
            $html .= "<th>Referee</th>";
            $html .= "<th>AR1</th>";
            $html .= "<th>AR2</th>";
            if ($has4th) {
                $html .= "<th>4th</th>";
            }
            $html .= "</tr>\n";

            $rowColor = $this->colorGroup1;
            $testtime = null;

            foreach ($games as $game) {
                if (!$this->justOpen || ($this->justOpen && (empty($game->cr) || empty($game->ar1) || empty($game->ar2)  || ( $has4th && empty($game->r4th))))) {
                    $date = date('D, d M', strtotime($game->date));
                    $time = date('H:i', strtotime($game->time));

                    if ( !$testtime ) {
                        $testtime = $time;
                    }
                    elseif ( ($testtime != $time && $game->assignor == $this->user->name) || ($testtime != $time && $this->user->admin && !empty($game->assignor))) {
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
                        if (empty($game->cr) && empty($game->ar1) && empty($game->ar2)) {
                            $html .= "<tr class=\"center\" bgcolor=\"$this->colorUnassigned\">";
                            //open AR  or 4th slots
                        }
                        elseif (empty($game->ar1) || empty($game->ar2) || ($has4th && empty($game->r4th))) {
                            $html .= "<tr class=\"center\" bgcolor=\"$this->colorOpenSlots\">";
                            //match covered
                        }
                        else {
                            $html .= "<tr class=\"center\" bgcolor=\"$rowColor\">";
                        }
                    } else {
                        $html .= "<tr class=\"center\" bgcolor=\"$this->colorLtGray\">";
                    }
                    if($this->user->admin){
                        //no assignor
                        if (empty($game->assignor)) {
                            $html .= "<tr class=\"center\" bgcolor=\"$this->colorUnassigned\">";
                            //my open slots
                        } elseif ($game->assignor == $this->user->name && empty($game->cr) && empty($game->ar1) && empty($game->ar2)) {
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
                    $html .= "<td>$game->field</td>";
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

            $this->menu = $this->menu('bottom');
        }

        return $html;

    }
    private function menu($pos='top')
    {
        $html = null;

        $html .= "<h3 class=\"center h3-btn\" style=\"margin-top: 20px; line-height: 3em;\">";

        if($pos == 'bottom') {
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
            $html .= "<a href=" . $this->getBaseURL('editGamePath') . ">Edit games</a>&nbsp;-&nbsp;";
            $html .= "<a  href=" . $this->getBaseURL('schedPath') . ">View Assignors</a>&nbsp;-&nbsp;";
            $html .= "<a  href=" . $this->getBaseURL('masterPath') . ">Select Assignors</a>&nbsp;-&nbsp;";
            $html .= "<a  href=" . $this->getBaseURL('refsPath') . ">Edit referee assignments</a>&nbsp;-&nbsp;";
        } else {
            $html .= "<a  href=" . $this->getBaseURL('schedPath') . ">Go to ". $this->user->name . " schedule</a>&nbsp;-&nbsp;";
            $html .= "<a  href=" . $this->getBaseURL('refsPath') . ">Edit ". $this->user->name . " referees</a>&nbsp;-&nbsp;";
        }

        $html .= "<a  href=" . $this->getBaseURL('endPath') . ">Log off</a><br>";

        if($pos == 'top') {
            $html .= "<a  href=" . $this->getBaseURL('fullXlsPath') . " class=\"btn btn-primary btn-xs export right\" style=\"margin-right: 0\">Export to Excel<i class=\"icon-white icon-circle-arrow-down\"></i></a>";
            $html .= "<div class='clear-fix'></div>";
        }

        $html .= "</h3>\n";

        return $html;
    }}