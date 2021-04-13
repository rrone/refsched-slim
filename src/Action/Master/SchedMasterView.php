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
    private $description;
    private $games;

    /**
     * SchedMasterView constructor.
     * @param Container $container
     * @param SchedulerRepository $schedulerRepository
     *
     */
    public function __construct(Container $container, SchedulerRepository $schedulerRepository)
    {
        parent::__construct($container, $schedulerRepository);

        $this->justOpen = false;
        $this->description = 'No matches scheduled';
        $this->games = null;

    }

    /**
     * @param Request $request
     * @param Response $response
     */
    public function handler(Request $request, Response $response)
    {
        $this->user = $request->getAttribute('user');
        $this->event = $request->getAttribute('event');

        $_GET = $request->getParams();
        if (count($_GET)) {
            $this->justOpen = array_key_exists('open', $_GET);
        }

        if ($request->isPost()) {
            //only Section 7 may update
            $data = $request->getParsedBody();

            $this->sr->updateAssignor($data);
        }
    }

    /**
     * @param Response $response
     *
     */
    public function render(Response $response)
    {
        $content = array(
            'view' => array(
                'admin' => $this->user->admin,
                'content' => $this->renderView(),
                'title' => $this->page_title,
                'dates' => $this->dates,
                'location' => $this->location,
                'description' => $this->description,
            )
        );

        $this->view->render($response, 'sched.html.twig', $content);
    }

    /**
     * @return string|null
     *
     */
    private function renderView()
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

            $this->games = $this->sr->getGames($projectKey, '%', true);

            if (count($this->games)) {
                $this->description = $this->user->name . ': Schedule Referee Teams';

                $select_list = array('');
                $users = $this->sr->getUsers($projectKey);

                foreach ($users as $user) {
                    if($user->name != 'Super Admin' && strpos($user->for_events, $projectKey) ) {
                        $select_list[] = $user->name;
                    }
                }

                $html .= "<form name=\"master_sched\" method=\"post\" action=".$this->getBaseURL('masterPath').">\n";

                $html .= $this->menu();
                $html .= "<h3 class=\"center\">Green: Assignments made (Boo-yah!) / Red: Needs your attention<br><br>\n";
                $html .= "Green shading change indicates different start times</h3>\n";

                $html .= "<table class=\"sched-table width100\">\n";
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
                            $html .= "<tr class=\"center colorUnassigned\">";
                        } else {
                            switch ($rowColor) {
                                case $this->colorGroup1:
                                    $html .= "<tr class=\"center colorGroup1\">";
                                    break;
                                default:
                                    $html .= "<tr class=\"center colorGroup2\">";
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
                        if(!$this->event->archived) {
                            $html .= "<td><select name=\"$game->id\">\n";
                            foreach ($select_list as $user) {
                                if(!strpos($user, 'Super Admin')) {
                                    if ($user == $game->assignor) {
                                        $html .= "<option selected>$user</option>\n";
                                    } else {
                                        $html .= "<option>$user</option>\n";
                                    }
                                }
                            }

                            $html .= "</select></td>";

                        } else {
                            $html .= "<td>$game->assignor</td>";
                        }
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

    /**
     * @return string
     *
     */
    private function menu()
    {
        $unassigned = $this->sr->getUnassignedGames($this->event->projectKey);

        $html = "<h3 class=\"center h3-btn\">";
        $html .= "<a href=" . $this->getBaseURL('greetPath') . ">Home</a>&nbsp;-&nbsp;";

        $html .= "<a href=" . $this->getBaseURL('fullPath') . ">View the full schedule</a> - ";

        if(!$this->event->archived) {
            $html .= "<a href=".$this->getBaseURL('editGamePath').">Edit matches</a>&nbsp;-&nbsp;";
        }

        if (count($unassigned)) {
            if ($this->justOpen) {
                $html .= "<a href=" . $this->getBaseURL('masterPath') . ">View all referee teams</a> - ";
            } else {
                $html .= "<a href=" . $this->getBaseURL('masterPath') . "?open>View open referee teams</a> - ";
            }
        }
        $html .= "<a href=" . $this->getBaseURL('schedPath') . ">View Assignors</a>&nbsp;-&nbsp;";
        $html .= "<a href=" . $this->getBaseURL('refsPath') . ">Edit Referee Assignments</a> - ";
        $html .= "<a href=" . $this->getBaseURL('endPath') . ">Log off</a>";

        if(count($this->games) && !$this->event->archived) {
            $html .= "<input class=\"btn btn-primary btn-xs right\" type=\"submit\" name=\"Submit\" value=\"Submit\">";
        }
        $html .= "<div class='clear-fix'></div>";

        $html .= "</h3>\n";

        return $html;

    }
}