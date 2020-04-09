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
    private $show_medal_round;
    private $show_medal_round_divisions;
    private $mr_games;
    private $show_medal_round_assignments;

    public function __construct(Container $container, SchedulerRepository $schedulerRepository)
    {
        parent::__construct($container, $schedulerRepository);

        $this->description = 'No matches scheduled';
        $this->games = null;
    }

    public function handler(Request $request, Response $response)
    {
        $this->user = $request->getAttribute('user');
        $this->event = $request->getAttribute('event');

        return null;
    }

    /**
     * @param Response $response
     *
     */
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
            ),
        );

        $this->view->render($response, 'sched.html.twig', $content);

    }

    /**
     * @return null|string
     *
     */
    private function renderRefs()
    {
        $html = null;

        if (!empty($this->event)) {
            $projectKey = $this->event->projectKey;
            //refresh event
            $this->event = $this->sr->getEvent($projectKey);

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
            $this->show_medal_round = $this->sr->getMedalRound($projectKey);
            $this->show_medal_round_divisions = $this->sr->getMedalRoundDivisions($projectKey);
            $this->show_medal_round_assignments = $this->sr->getMedalRoundAssignedNames($projectKey);

            if ($this->user->admin) {
                $this->games = $this->sr->getGames($projectKey, '%', true);
            } else {
                $this->games = $this->sr->getGames($projectKey, '%', $this->show_medal_round);
            }

            if (!$this->show_medal_round_assignments) {
                $games = [];
                $this->mr_games = [];
                foreach ($this->games as $game) {
                    switch ($game->pool) {
                        case 'SF':
                        case 'FIN':
                        case 'CON':
                            $this->mr_games[] = $game;
                            break;
                        default:
                            $games[] = $game;
                    }
                }
                $this->games = $games;
            }

            $refNames = [];
            if ($this->user->admin) {
                $refs = $this->sr->getPersonInfo('%');
                foreach ($refs as $ref) {
                    $refNames[] = $ref['Nickname'];
                }
            }

            if (count($this->games)) {
                $this->description = $this->user->name.': Referee Assignments';

                foreach ($this->games as $game) {
                    if ($game->assignor == $this->user->name || $this->user->admin) {
                        $this->num_assigned++;
                    }
                }

                $has4th = $this->sr->numberOfReferees($projectKey) > 3;

                if ($this->num_assigned) {
                    $html .= "<h3 class=\"center\">Green: Assignments covered (Boo-yah!) / Yellow: Open Slots / Red: Needs your attention / Grey: Not yours to cover<br><br>\n";
                    $html .= "Green shading change indicates different start times</h3>\n";

                    $html .= "<form name=\"addref\" method=\"post\" action=\"".$this->getBaseURL('refsPath')."\">\n";
                    $html .= $this->renderGames($this->games, $refNames, $has4th);

                    if(!$this->show_medal_round_assignments) {
                        $html .= "<br><br>";
                        $html .= "<h2 class=\"center\">For Medal Rounds, Referee Names are placeholders only.  No assignment as Referee or Assistant Referee should be inferred.</h2>\n";
                        $html .= "<h2 class=\"center\"><em><u>Please</u></em> let your Referees know the assignments for the medal rounds will be at the field by the Section Assignor.</h2>\n";
                        $html .= "<br><br>";
                        $html .= $this->renderGames($this->mr_games, $refNames, $has4th,
                            !$this->show_medal_round_assignments);
                    }
                    $html .= "</form>\n";

                    $this->bottommenu = $this->menu();
                } else {
                    if (count($this->games)) {
                        $html .= "<h2 class=\"center\">You do not currently have any matches scheduled.</h2>\n";
                    }
                    $this->bottommenu = null;
                }
            }
        }

        return $html;

    }

    /**
     * @param $games
     * @param $refNames
     * @param $has4th
     * @param bool $mr
     * @return string|null
     */
    private function renderGames($games, $refNames, $has4th, $mr = false)
    {
        if (empty($games)) {
            return null;
        }

        $html = "<table class=\"sched-table width100\">\n";
        $html .= "<tr class=\"center colorTitle\">";
        $html .= "<th>Match #</th>";
        $html .= "<th>Date</th>";
        $html .= "<th>Time</th>";
        $html .= "<th>Field</th>";
        $html .= "<th>Division</th>";
        $html .= "<th>Pool</th>";
        $html .= "<th>Home</th>";
        $html .= "<th>Away</th>";
        $html .= "<th>Referee Team</th>";
        if(!$mr){
            $html .= "<th>Referee</th>";
            $html .= "<th>AR1</th>";
            $html .= "<th>AR2</th>";
            if ($has4th) {
                $html .= "<th>4th</th>";
            }
        } else {
            $html .= "<th>Referee Name</th>";
            $html .= "<th>Referee Name</th>";
            $html .= "<th>Referee Name</th>";
            if ($has4th) {
                $html .= "<th>Referee Name</th>";
            }
        }
        if (!$this->event->archived) {
            $html .= "<th>Edit</th>";
        }
        $html .= "</tr>\n";

        $rowColor = $this->colorGroup1;
        $testtime = null;

        foreach ($games as $game) {
            $date = date('D, d M', strtotime($game->date));
            $time = date('H:i', strtotime($game->time));
            if ($game->assignor == $this->user->name || $this->user->admin) {
                if (!$game->assignor && $this->user->admin) {
                    $html .= "<tr class=\"center colorOpenSlots\">";
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
                    if (empty($game->cr) && empty($game->ar1) && empty($game->ar2) && (!$has4th || ($has4th
                                && empty($game->r4th)))) {
                        $html .= "<tr class=\"center colorUnassigned\">";
                        //open AR  or 4th slots
                    } elseif (empty($game->cr) || empty($game->ar1) || empty($game->ar2) || ($has4th &&
                            empty($game->r4th))) {
                        $html .= "<tr class=\"center colorOpenSlots\">";
                        //match covered
                    } else {
                        switch ($rowColor) {
                            case $this->colorGroup1:
                                $html .= "<tr class=\"center colorGroup1\">";
                                break;
                            default:
                                $html .= "<tr class=\"center colorGroup2\">";
                        }
                    }
                }
                if ($this->show_medal_round_divisions || !$game->medalRound || $this->user->admin) {
                    $html .= "<td>$game->game_number</td>";
                } else {
                    $html .= "<td></td>";
                }
                $html .= "<td>$date</td>";
                $html .= "<td>$time</td>";
                if ($this->show_medal_round_divisions || !$game->medalRound || $this->user->admin) {
                    if (is_null($this->event->field_map)) {
                        $html .= "<td>$game->field</td>";
                    } else {
                        $html .= "<td><a href='".$this->event->field_map."' target='_blank'>$game->field</a></td>";
                    }
                    $html .= "<td>$game->division</td>";
                    $html .= "<td>$game->pool</td>";
                    $html .= "<td>$game->home</td>";
                    $html .= "<td>$game->away</td>";
                } else {
                    $html .= "<td></td>";
                    $html .= "<td></td>";
                    $html .= "<td></td>";
                    $html .= "<td></td>";
                    $html .= "<td></td>";
                }
                $html .= "<td>$game->assignor</td>";
                if ($this->user->admin && !empty($game->cr) && count(preg_grep("/$game->cr/", $refNames))) {
                    $html .= '<td><button type="button" class="info btn btn-link" id="'.$game->cr.'">'.$game->cr.'</button></td>';
                } else {
                    $html .= "<td>$game->cr</td>";
                }
                if ($this->user->admin && !empty($game->ar1) && count(
                        preg_grep("/$game->ar1/", $refNames)
                    )) {
                    $html .= '<td><button type="button" class="info btn btn-link" id="'.$game->ar1.'">'.$game->ar1.'</button></td>';
                } else {
                    $html .= "<td>$game->ar1</td>";
                }
                if ($this->user->admin && !empty($game->ar2) && count(
                        preg_grep("/$game->ar2/", $refNames)
                    )) {
                    $html .= '<td><button type="button" class="info btn btn-link" id="'.$game->ar2.'">'.$game->ar2.'</button></td>';
                } else {
                    $html .= "<td>$game->ar2</td>";
                }
                if ($has4th) {
                    if ($this->user->admin && !empty($game->r4th) && count(
                            preg_grep(
                                "/$game->r4th/",
                                $refNames
                            )
                        )) {
                        $html .= '<td><button type="button" class="info btn btn-link" id="'.$game->r4th
                            .'">'.$game->r4th.'</button></td>';
                    } else {
                        $html .= "<td>$game->r4th</td>";
                    }
                }
                if (!$this->event->archived) {
                    $disabled = $game->locked && !$this->user->admin;
                    $html .= "<td><input class=\"btn btn-primary btn-xs \" type=\"submit\" name=\"$game->id\" value=\"Edit Assignments\" ";
                    if ($game->assignor || $this->user->admin) {
                        $html .= $disabled ? " disabled ></td>" : "></td>";
                    } else {
                        $html .= "<td>&nbsp;</td>\n";
                    }
                }
                $html .= "</tr>\n";
            }
        }
        $html .= "</table>\n";

        return $html;
    }
    /**
     * @return string
     *
     */
    private function menu()
    {
        $html = "<h3 class=\"center h3-btn\">";

        $html .= "<a href=".$this->getBaseURL('greetPath').">Home</a>&nbsp;-&nbsp;";

        $html .= "<a href=".$this->getBaseURL('fullPath').">View the full schedule</a>&nbsp;-&nbsp";

        if ($this->user->admin) {
            if (!$this->event->archived) {
                $html .= "<a href=".$this->getBaseURL('editGamePath').">Edit matches</a>&nbsp;-&nbsp;";
            }
            $html .= "<a href=".$this->getBaseURL('schedPath').">View Assignors</a>&nbsp;-&nbsp;";
            $html .= "<a href=".$this->getBaseURL('masterPath').">Select Assignors</a>&nbsp;-&nbsp;";
        } else {
            $html .= "<a href=".$this->getBaseURL('schedPath').">Go to ".$this->user->name." schedule</a>&nbsp;-&nbsp;";
        }

        $html .= "<a href=".$this->getBaseURL('endPath').">Log off</a>";

        $html .= "</h3>\n";

        return $html;
    }
}