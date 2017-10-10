<?php

namespace App\Action\Refs;

use Slim\Container;
use App\Action\SchedulerRepository;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractView;

class SchedRefsView extends AbstractView
{
    private $num_assigned;
    private $bottommenu;
    private $description;
    private $games;

    public function __construct(Container $container, SchedulerRepository $schedulerRepository)
    {
        parent::__construct($container, $schedulerRepository);

        $this->description = 'No games scheduled';
        $this->games = null;
    }

    public function handler(Request $request, Response $response)
    {
        $this->user = $request->getAttribute('user');
        $this->event = $request->getAttribute('event');

        return null;
    }

    public function render(Response &$response)
    {
        $content = array(
            'view' => array(
                'admin' => $this->user->admin,
                'content' => $this->renderRefs(),
                'topmenu' => $this->menu(),
                'menu' => $this->bottommenu,
                'title' => $this->page_title,
                'dates' => $this->dates,
                'location' => $this->location,
                'description' => $this->description,
            )
        );

        $this->view->render($response, 'sched.html.twig', $content);

    }

    private function renderRefs()
    {
        $html = null;

        if (!empty($this->event)) {
            if (!empty($this->event->infoLink)) {
                $eventLink = $this->event->infoLink;
                $eventName = $this->event->name;
                $eventName = "<a href='$eventLink' target='_blank'>$eventName</a>";
            } else {
                $eventName = $this->event->name;
            }

            $this->page_title = $eventName;
            $this->dates = $this->event->dates;
            $this->location = $this->event->location;
            $projectKey = $this->event->projectKey;
            $show_medal_round = $this->sr->getMedalRound($projectKey);

            if ($this->user->admin) {
                $this->games = $this->sr->getGames($projectKey, '%', true);
            } else {
                $this->games = $this->sr->getGames($projectKey, '%', $show_medal_round);
            }

            if(count($this->games)) {
                $this->description = $this->user->name . ': Referee Assignments';

                foreach ($this->games as $game) {
                    if ($game->assignor == $this->user->name || $this->user->admin) {
                        $this->num_assigned++;
                    }
                }

                $has4th = $this->sr->numberOfReferees($projectKey) > 3;

                if ($this->num_assigned) {
                    $html .= "<h3 class=\"center\">Green: Assignments covered (Yah!) / Yellow: Open Slots / Red: Needs your attention / Grey: Not yours to cover<br><br>\n";
                    $html .= "Green shading change indicates different start times</h3>\n";

                    $html .= "<form name=\"addref\" method=\"post\" action=\"".$this->getBaseURL('refsPath')."\">\n";
                    $html .= "<table class=\"sched-table\" width=\"100%\">\n";
                    $html .= "<tr class=\"center\" bgcolor=\"$this->colorTitle\">";
                    $html .= "<th>Game#</th>";
                    $html .= "<th>Date</th>";
                    $html .= "<th>Time</th>";
                    $html .= "<th>Field</th>";
                    $html .= "<th>Division</th>";
                    $html .= "<th>Pool</th>";
                    $html .= "<th>Home</th>";
                    $html .= "<th>Away</th>";
                    $html .= "<th>Referee Team</th>";
                    $html .= "<th>CR</th>";
                    $html .= "<th>AR1</th>";
                    $html .= "<th>AR2</th>";
                    if ($has4th) {
                        $html .= "<th>4th</th>";
                    }
                    if(!$this->event->archived) {
                        $html .= "<th>Edit</th>";
                    }
                    $html .= "</tr>\n";

                    $rowColor = $this->colorGroup1;
                    $testtime = null;

                    foreach ($this->games as $game) {
                        $date = date('D, d M', strtotime($game->date));
                        $time = date('H:i', strtotime($game->time));
                        if ($game->assignor == $this->user->name || $this->user->admin) {
                            if (!$game->assignor && $this->user->admin) {
                                $html .= "<tr class=\"center\" bgcolor=\"$this->colorOpenSlots\">";
                            } else {
                                if (!$testtime) {
                                    $testtime = $time;
                                } elseif ($testtime != $time && !empty($game->assignor)) {
                                    $testtime = $time;
                                    switch ($rowColor) {
                                        case $this->colorGroup1:
                                            $rowColor = $this->colorGroup2;
                                            break;
                                        default:
                                            $rowColor = $this->colorGroup1;
                                    }
                                }
                                //no refs
                                if (empty($game->cr) && empty($game->ar1) && empty($game->ar2)) {
                                    $html .= "<tr class=\"center\" bgcolor=\"$this->colorUnassigned\">";
                                    //open AR  or 4th slots
                                } elseif (empty($game->ar1) || empty($game->ar2) || ($has4th && empty($game->r4th))) {
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
                            if(!$this->event->archived) {
                                $locked = $game->locked && !$this->user->admin ? 'disabled' : '';
                                if ($game->assignor || $this->user->admin) {
                                    $html .= "<td><input class=\"btn btn-primary btn-xs \" type=\"submit\" name=\"$game->id\" value=\"Edit Assignments\" $locked></td>";
                                } else {
                                    $html .= "<td>&nbsp;</td>\n";
                                }
                            }
                            $html .= "</tr>\n";
                        }
                    }
                    $html .= "</table>\n";
                    $html .= "</form>\n";

                    $this->bottommenu = $this->menu();
                } else {
                    if (count($this->games)) {
                        $html .= "<h2 class=\"center\">You do not currently have any games scheduled.</h2>\n";
                    }
                    $this->bottommenu = null;
                }
            }
        }

        return $html;

    }

    private function menu()
    {
        $html = "<h3 class=\"center h3-btn\">";

        $html .= "<a href=" . $this->getBaseURL('greetPath') . ">Home</a>&nbsp;-&nbsp;";

        $html .= "<a href=" . $this->getBaseURL('fullPath') . ">View the full schedule</a>&nbsp;-&nbsp";

        if ($this->user->admin) {
            if(!$this->event->archived) {
                $html .= "<a href=".$this->getBaseURL('editGamePath').">Edit games</a>&nbsp;-&nbsp;";
            }
            $html .= "<a href=" . $this->getBaseURL('schedPath') . ">View Assignors</a>&nbsp;-&nbsp;";
            $html .= "<a href=" . $this->getBaseURL('masterPath') . ">Select Assignors</a>&nbsp;-&nbsp;";
        } else {
            $html .= "<a href=" . $this->getBaseURL('schedPath') . ">Go to " . $this->user->name . " schedule</a>&nbsp;-&nbsp;";
        }

        $html .= "<a href=" . $this->getBaseURL('endPath') . ">Log off</a>";

        $html .= "</h3>\n";

        return $html;
    }
}