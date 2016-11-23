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

    public function __construct(Container $container, SchedulerRepository $schedulerRepository)
    {
        parent::__construct($container, $schedulerRepository);
    }

    public function handler(Request $request, Response $response)
    {
        $this->user = $request->getAttribute('user');
        $this->event = $request->getAttribute('event');

        if ($request->isPost()) {
            $_POST = $request->getParsedBody();

            $_SESSION['game_id'] = array_keys($_POST);
        } else {
            unset($_SESSION['game_id']);
        }

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
                'description' => $this->user->name . ' Referee Assignments',
            )
        );

        $this->view->render($response, 'sched.html.twig', $content);

    }

    private function renderRefs()
    {
        $html = null;

        if (!empty($this->event)) {
            $this->page_title = $this->event->name;
            $this->dates = $this->event->dates;
            $this->location = $this->event->location;
            $projectKey = $this->event->projectKey;

            if ($this->user->admin) {
                $games = $this->sr->getGames($projectKey, '%', true);
            } else {
                $games = $this->sr->getGames($projectKey);
            }

            foreach ($games as $game) {
                if ($game->assignor == $this->user->name || $this->user->admin) {
                    $this->num_assigned++;
                }
            }

            $has4th = $this->sr->numberOfReferees($projectKey) > 3;

            if ($this->num_assigned) {
                if (!$this->user->admin) {
                    $html .= "<h2  class=\"center\">You are currently scheduled for the following games</h2></div>\n";
                }
                $html .= "<h3 class=\"center\">Green: Assignments covered (Yah!) / Yellow: Open Slots / Red: Needs your attention / Grey: Not yours to cover<br><br>\n";
                $html .= "Green shading change indicates different start times</h3>\n";

                $html .= "<form name=\"addref\" method=\"post\" action=\"" . $this->getBaseURL('refsPath') . "\">\n";
                $html .= "<table class=\"sched-table\" width=\"100%\">\n";
                $html .= "<tr align=\"center\" bgcolor=\"$this->colorTitle\">";
                $html .= "<th>Game No.</th>";
                $html .= "<th>Date</th>";
                $html .= "<th>Time</th>";
                $html .= "<th>Field</th>";
                $html .= "<th>Division</th>";
                $html .= "<th>Pool</th>";
                $html .= "<th>Home</th>";
                $html .= "<th>Away</th>";
                $html .= "<th>Area</th>";
                $html .= "<th>CR</th>";
                $html .= "<th>AR1</th>";
                $html .= "<th>AR2</th>";
                if ($has4th) {
                    $html .= "<th>4th</th>";
                }
                $html .= "<th>Edit</th>";
                $html .= "</tr>\n";

                $rowColor = $this->colorGroup1;
                $testtime = null;

                foreach ($games as $game) {
                    $date = date('D, d M', strtotime($game->date));
                    $time = date('H:i', strtotime($game->time));
                    if ($game->assignor == $this->user->name || $this->user->admin) {
                        if (!$game->assignor && $this->user->admin) {
                            $html .= "<tr align=\"center\" bgcolor=\"$this->colorOpenSlots\">";
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
                                $html .= "<tr align=\"center\" bgcolor=\"$this->colorUnassigned\">";
                                //open AR  or 4th slots
                            } elseif (empty($game->ar1) || empty($game->ar2) || ($has4th && empty($game->r4th))) {
                                $html .= "<tr align=\"center\" bgcolor=\"$this->colorOpenSlots\">";
                                //match covered
                            } else {
                                $html .= "<tr align=\"center\" bgcolor=\"$rowColor\">";
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
                        if ($game->assignor || $this->user->admin) {
                            $html .= "<td><input class=\"btn btn-primary btn-xs \" type=\"submit\" name=\"$game->id\" value=\"Edit Assignments\"></td>";
                        } else {
                            $html .= "<td>&nbsp;</td>\n";
                        }
                        $html .= "</tr>\n";
                    }
                }
                $html .= "</table>\n";
                $html .= "</form>\n";

                $this->bottommenu = $this->menu();
            } else {
                $html .= "<h2 class=\"center\">You do not currently have any games scheduled.</h2>\n";
                $this->bottommenu = "<h3 class=\"center\">You should go to the <a href=" . $this->getBaseURL('schedPath') . ">Schedule Page</a></h3>";
            }
        } else {
            $html .= $this->errorCheck();
        }

        return $html;

    }

    private function menu()
    {
        $html = "<h3 align=\"center\"><a href=" . $this->getBaseURL('greetPath') . ">Home</a>&nbsp;-&nbsp;\n";

        $html .= "<a href=" . $this->getBaseURL('fullPath') . ">View the full schedule</a>&nbsp;-&nbsp\n";

        if ($this->user->admin) {
            $html .= "<a href=" . $this->getBaseURL('schedPath') . ">View Assignors</a>&nbsp;-&nbsp;\n";
            $html .= "<a href=" . $this->getBaseURL('masterPath') . ">Select Assignors</a>&nbsp;-&nbsp;\n";
        } else {
            $html .= "<a href=" . $this->getBaseURL('schedPath') . ">Go to " . $this->user->name . " schedule</a>&nbsp;-&nbsp;\n";
        }

        $html .= "<a href=" . $this->getBaseURL('endPath') . ">Log off</a></h3>\n";

        return $html;
    }
}