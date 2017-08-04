<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 11/7/16
 * Time: 1:40 PM
 */

namespace App\Action\Master;

use Slim\Container;
use App\Action\SchedulerRepository;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractView;

class SchedMasterView extends AbstractView
{
    private $topmenu;
    private $bottommenu;
    private $justOpen;
    private $description;
    private $games;

    public function __construct(Container $container, SchedulerRepository $schedulerRepository)
    {
        parent::__construct($container, $schedulerRepository);

        $this->justOpen = false;
        $this->description = 'No games scheduled';
        $this->games = null;

    }

    public function handler(Request $request, Response $response)
    {
        $this->user = $request->getAttribute('user');
        $this->event = $request->getAttribute('event');

        $_GET = $request->getParams();
        if (count($_GET)) {
            $this->justOpen = array_key_exists('open', $_GET);
        }

        if ($request->isPost()) {
            //only Section 1 may update
            $data = $request->getParsedBody();

            $this->sr->updateAssignor($data);
        }
    }

    public function render(Response &$response)
    {
        $content = array(
            'view' => array(
                'admin' => $this->user->admin,
                'content' => $this->renderView(),
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

    private function renderView()
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

            $this->games = $this->sr->getGames($projectKey, '%', true);

            if (count($this->games)) {
                $this->description = $this->user->name . ': Schedule Referee Teams';

                $select_list = array('');
                $users = $this->sr->getAllUsers($projectKey);

                foreach ($users as $user) {
                    $select_list[] = $user->name;
                }

                $select_list[] = 'To Be Announced';

                $html .= "<form name=\"master_sched\" method=\"post\" action=".$this->getBaseURL('masterPath').">\n";

                $html .= "<h3 class=\"center\">Green: Assignments made (Yah!) / Red: Needs your attention<br><br>\n";
                $html .= "Green shading change indicates different start times</h3>\n";

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
                $html .= "</tr>\n";

                $rowColor = $this->colorGroup1;
                $testtime = null;

                foreach ($this->games as $game) {
                    if (!$this->justOpen || ($this->justOpen && empty($game->assignor))) {
                        $date = date('D, d M', strtotime($game->date));
                        $time = date('H:i', strtotime($game->time));

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

                        if (empty($game->assignor)) {
                            $html .= "<tr class=\"center\" bgcolor=\"$this->colorUnassigned\">";
                        } else {
                            $html .= "<tr class=\"center\" bgcolor=\"$rowColor\">";
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

                        $html .= "<td><select name=\"$game->id\">\n";
                        foreach ($select_list as $user) {
                            if ($user == $game->assignor) {
                                $html .= "<option selected>$user</option>\n";
                            } else {
                                $html .= "<option>$user</option>\n";
                            }
                        }

                        $html .= "</select></td>";
                        $html .= "</tr>\n";
                    }
                }
                $html .= "</table>\n";

                $html .= $this->menu();

                $html .= "</form>\n";
                $this->topmenu = null;
                $this->bottommenu = null;
            }
        }

        return $html;

    }

    private function menu()
    {
        $unassigned = $this->sr->getUnassignedGames($this->event->projectKey);

        $html = "<h3 class=\"center h3-btn\">";
        $html .= "<a href=" . $this->getBaseURL('greetPath') . ">Home</a>&nbsp;-&nbsp;";

        $html .= "<a href=" . $this->getBaseURL('fullPath') . ">View the full schedule</a> - ";

        $html .= "<a href=" . $this->getBaseURL('editGamePath') . ">Edit games</a>&nbsp;-&nbsp;";
        if (count($unassigned)) {
            if ($this->justOpen) {
                $html .= "<a href=" . $this->getBaseURL('masterPath') . ">View all referee teams</a> - ";
            } else {
                $html .= "<a href=" . $this->getBaseURL('masterPath') . "?open>View open referee teams</a> - ";
            }
        }
        $html .= "<a href=" . $this->getBaseURL('schedPath') . ">View Assignors</a>&nbsp;-&nbsp;";
        $html .= "<a href=" . $this->getBaseURL('refsPath') . ">Edit referee assignments</a> - ";
        $html .= "<a href=" . $this->getBaseURL('endPath') . ">Log off</a>";

        if(count($this->games)) {
            $html .= "<input class=\"btn btn-primary btn-xs right\" type=\"submit\" name=\"Submit\" value=\"Submit\">";
        }
        $html .= "<div class='clear-fix'></div>";

        $html .= "</h3>\n";

        return $html;

    }
}