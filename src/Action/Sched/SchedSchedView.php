<?php

namespace App\Action\Sched;

//use function FastRoute\TestFixtures\empty_options_cached;
use Exception;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\SchedulerRepository;
use App\Action\AbstractView;

/**
 * Class SchedSchedView
 * @package App\Action\Sched
 */
class SchedSchedView extends AbstractView
{
    private $description;
    private $showgroup;
    private $show_medal_round;
    private $show_medal_round_divisions;
    private $locked;

    private $num_unassigned;
    private $atlimit;

    /**
     * SchedSchedView constructor.
     * @param Container $container
     * @param SchedulerRepository $schedulerRepository
     *
     */
    public function __construct(Container $container, SchedulerRepository $schedulerRepository)
    {
        parent::__construct($container, $schedulerRepository);

        $this->showgroup = null;
        $this->description = 'No matches scheduled';

        $this->atlimit = false;

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return null
     */
    public function handler(Request $request, Response $response)
    {
        $this->user = $request->getAttribute('user');
        $this->event = $request->getAttribute('event');

        if ($request->isPost()) {

            $this->projectKey = $this->event->projectKey;

            $this->locked = $this->sr->getLocked($this->projectKey);

            $this->initLimitLists();

            $this->msg = null;

            $_POST = $request->getParsedBody();
            $array_of_keys = array_keys($_POST);

            //parse the POST data
            $this->showgroup = !empty($_POST['group']) ? $_POST['group'] : null;

            $adds = [];
            $assign = [];
            $atLimit = [];
            $games_now = [];
            $div = null;

            foreach ($array_of_keys as $key) {
                $change = explode(':', $key);
                switch ($change[0]) {
                    case 'assign':
                        $adds[$change[1]] = $this->user->name;
                        break;
                    case 'matches':
                        $assign[$change[1]] = $this->user->name;
                        break;
                    default:
                        break;
                }
            }

            if (!$this->locked) {
                //remove drops if not locked
                $assigned_games = $this->sr->getGamesByRep($this->projectKey, $this->user->name, true);

                if (count($assign) != count($assigned_games)) {
                    $removed = [];
                    $unassign = [];
                    foreach ($assigned_games as $game) {
                        if (!in_array($game->id, array_keys($assign))) {
                            if (is_null($this->showgroup) || ($this->showgroup == $this->divisionAge(
                                        $game->division
                                    ))) {
                                $removed[$game->id] = $game;
                                $unassign[$game->id] = '';
                                $data = array(
                                    'cr' => '',
                                    'ar1' => '',
                                    'ar2' => '',
                                    'r4th' => '',
                                    $game->id => 'Update Assignments',
                                );
                                $this->sr->updateAssignments($data);
                                $this->msg .= "<p>You have <strong>removed</strong> your referee team from $game->division Match # $game->game_number on $game->date at $game->time on $game->field</p>";
                            }
                        }
                    }

                    $this->sr->updateAssignor($unassign);
                }
            }

            //initialize counting groups
            $assigned_games = $this->sr->getGamesByRep($this->projectKey, $this->user->name, true);
            foreach ($assigned_games as $game) {
                $div = $this->divisionAge($game->division);
                if (!isset($games_now[$div])) {
                    $games_now[$div] = 0;
                }
                $games_now[$div]++;
            }

            if (count($adds)) {
                //Update based on add/returned matches
                $added = [];
                $unavailable = [];
                $games = $this->sr->getGames($this->projectKey, '%', true);
                foreach ($games as $game) {
                    if (empty($game->division)) {
                        continue;
                    }
                    $date = date('D, d M', strtotime($game->date));
                    $time = date('H:i', strtotime($game->time));
                    $div = $game->division;
                    //ensure all indexes exist
                    $games_now[$div] = isset($games_now[$div]) ? $games_now[$div] : 0;
                    $atLimit[$div] = isset($atLimit[$div]) ? $atLimit[$div] : 0;
                    //if requested

                    if (in_array($game->id, array_keys($adds))) {
                        //and available
                        if (empty($game->assignor)) {
                            //and below the limit if there is one

                            if (($games_now[$div] < $this->limit_list[$div]) || $this->limit_list[$div] == 'none') {
                                //make the assignment
                                $data = [$game->id => $this->user->name];
                                $this->sr->updateAssignor($data);
                                $added[$game->id] = $game;
                                $games_now[$game->division]++;
                                $this->msg .= "<p>You have <strong>scheduled</strong> $div Match # $game->game_number on $date on $game->field at $time</p>";
                            } else {
                                $atLimit[$game->division]++;
                                $this->msg .= "<p>You have <strong>not scheduled</strong> $div Match # $game->game_number on $date on $game->field at $time because you are at your match limit!</p>";
                            }
                        } else {
                            $unavailable[$game->id] = $game;
                            $this->msg = "<p>Sorry, $div Match # $game->game_number has been scheduled by $game->assignor</p>";
                        }
                    }
                }
            }
        }

        $_GET = $request->getParams();
        if (count($_GET) && array_key_exists('group', $_GET)) {
            $this->showgroup = $_GET['group'];
        }

        return null;
    }

    /**
     * @param Response $response
     *
     * @throws Exception
     */
    public function render(Response $response)
    {
        $content = array(
            'view' => array(
                'admin' => $this->user->admin,
                'content' => $this->renderView(),
                'topmenu' => null,
                'menu' => null,
                'title' => $this->page_title,
                'dates' => $this->dates,
                'location' => $this->location,
                'description' => $this->description,
                'message' => $this->msg,
            ),
        );

        $this->view->render($response, 'sched.html.twig', $content);

    }

    /**
     * @return string|null
     * @throws Exception
     */
    private function renderView()
    {
        $html = null;

        $assignors = [];

        if (!empty($this->event)) {
            $this->projectKey = $this->event->projectKey;
            //refresh event
            $this->event = $this->sr->getEvent($this->projectKey);
            $users = $this->sr->getUsers($this->projectKey);

            $this->initLimitLists();

            //Build header block
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

            //initialize page variables / flags
            $this->locked = $this->sr->getLocked($this->projectKey);
            $_SESSION['locked'] = $this->locked;

            $this->show_medal_round = $this->sr->getMedalRound($this->projectKey);
            $this->show_medal_round_divisions = $this->sr->getMedalRoundDivisions($this->projectKey);

            $html .= $this->renderUserStatus();

            $submitDisabled = (empty($this->assigned_list['assigned']) && empty($this->assigned_list['unassigned'])) || $this->user->admin || $this->event->archived || $this->atlimit;

            $html .= "<form name=\"form1\" method=\"post\" action=" . $this->getBaseURL('schedPath') . ">\n";

            $html .= "<h3 class=\"center h3-btn\">";
            $html .= $this->menuLinks();
            if (!$submitDisabled) {
                $html .= "<input class=\"btn btn-primary btn-xs right\" type=\"submit\" name=\"Submit\" value=\"Submit\">";
            }
            $html .= "<div class='clear-fix'></div>\n";
            $html .= "</h3>";

            $html .= $this->renderAvailableGames();

            if (!$this->user->admin) {
                $html .= $this->renderAssignmentByArea($this->user->name);
            } else {
                foreach ($users as $user) {
                    if (!$user->admin) {
                        $assignors[] = $user->name;
                    }
                }

                foreach ($assignors as $refTeam) {
                    $html .= $this->renderAssignmentByArea($refTeam);
                }
            }

            $html .= "<h3 class=\"center h3-btn\">";
            $html .= $this->menuLinks();
            if (!$submitDisabled) {
                $html .= "<input class=\"btn btn-primary btn-xs right\" type=\"submit\" name=\"Submit\" value=\"Submit\">";
            }
            $html .= "<div class='clear-fix'></div>";
            $html .= "</h3>\n";

            $html .= "</form>\n";

        }

        return $html;
    }

    /**
     * @return string
     *
     */
    private function menuLinks()
    {
        $uname = $this->user->name;

        $html = "<a href=" . $this->getBaseURL('greetPath') . ">Home</a>&nbsp;-&nbsp;";
        $html .= "<a href=" . $this->getBaseURL('fullPath') . ">View the full schedule</a>&nbsp;-&nbsp;";

        if ($this->user->admin) {
            if (!$this->event->archived) {
                $html .= "<a href=" . $this->getBaseURL('editGamePath') . ">Edit matches</a>&nbsp;-&nbsp;";
            }
            $html .= "<a href=" . $this->getBaseURL('masterPath') . ">Select Assignors</a>&nbsp;-&nbsp;";
            $html .= "<a href=" . $this->getBaseURL('refsPath') . ">Edit Referee Assignments</a>&nbsp;-&nbsp;";
        } elseif ($this->showgroup) {
            $html .= "<a href=" . $this->getBaseURL('schedPath') . ">View all $uname matches</a>&nbsp;-&nbsp;";
        }
        if (!empty($this->assigned_list['assigned'])) {
            $html .= "<a href=" . $this->getBaseURL('refsPath') . ">Edit $uname Referee Assignments</a>&nbsp;-&nbsp;";
        }

        $html .= "<a href=" . $this->getBaseURL('endPath') . ">Log off</a>";

        return $html;
    }


    /**
     * @return string|null
     */
    private function renderUserStatus()
    {
        $html = null;

        $games = $this->sr->getGames($this->projectKey, $this->showgroup, true);

        if (count($games)) {
            $this->description = $this->user->name . ': Schedule';

            $this->num_unassigned = count($games);

            $this->assigned_list['assigned'] = [];
            $this->assigned_list['unassigned'] = [];

            foreach ($games as $game) {
                if ($this->show_medal_round_divisions) {

                    if (!empty($game->assignor)) {
                        $this->num_unassigned--;

                        if ($game->assignor == $this->user->name) {
                            if (isset($this->assigned_list[$game->division])) {
                                $this->assigned_list[$game->division]++;
                            } else {
                                $this->assigned_list[$game->division] = 1;
                            }
                            $this->assigned_list['assigned'][] = $game;
                        }
                    } else {
                        $this->assigned_list['unassigned'][] = $game;
                    }
                }
            }

            //for all users but Admins
            if (!$this->user->admin) {
                if ($this->locked) {
                    $html .= "<h3 class=\"center\"><span style=\"color:$this->colorAlert\">The schedule is locked</span></h3>\n";
                }
                $html .= "<h3 class=\"center\">\n";
                foreach ($this->assigned_list as $div => $v) {
                    if (!empty($div) && !$this->locked) {
                        if (!in_array($div, ['assigned', 'unassigned'])) {
                            $temp_assign = $this->assigned_list[$div];
                            $temp_limit = $this->limit_list[$div];
                            if (!$this->showgroup || $this->showgroup == $div) {
                                $d = str_replace('_', ' ', $div);
                                if (isset($this->limit_list[$div]) && $this->limit_list[$div] != 'none') {
                                    $html .= "For $d, you are assigned to <span style=\"color:$this->colorAlert\">$temp_assign</span> matches with a limit of <span style=\"color:$this->colorAlert\">$temp_limit</span> matches<br>";
                                } else {
                                    $html .= "For $d, you are assigned to <span style=\"color:$this->colorAlert\">$temp_assign</span> matches with no limit<br>";
                                }
                            }
                        }
                    }
                }
                $html .= "</h3>\n";

                $role = $this->view['assignorrole'];
                foreach ($this->limit_list as $div => $limit) {
                    //schedule is locked
                    if ($this->locked) {
                        //if limit applies to all
                        //if below limit
                        if ($limit != 'none' && $this->assigned_list[$div] < $limit) {
                            $html .= "<h3 class=\"center\"><span style=\"color:$this->colorAlert\">You may sign up for $div matches but not unassign yourself</span></h3>\n";
                        }
                        //if at or above limit
                        if ($limit != 'none' && $this->assigned_list[$div] >= $limit) {
                            $html .= "<h3 class=\"center\"><span style=\"color:$this->colorAlert\">You are at or above your $div match limit in one or more divisions<br>You need to contact the $role if you want to change your match assignments</span></h3>\n";
                            $this->atlimit |= true;
                        }
                    } else {  // not locked
                        //if at limit
                        if ($limit != 'none' && $this->assigned_list[$div] == $limit) {
                            $html .= "<h3 class=\"center\"><span style=\"color:$this->colorAlert\">You are at your $div match limit in one or more divisions<br>You will have to unassign yourself from $div matches to sign up for others</span></h3>\n";
                            $this->atlimit &= true;
                        }
                        //if above limit
                        if ($limit != 'none' && $this->assigned_list[$div] > $limit) {
                            $html .= "<h3 class=\"center\"><span style=\"color:$this->colorAlert\">You are above your $div match limit<br>Additional matches were likely assigned to you by the $role</span></h3>\n";
                            $this->atlimit &= true;
                        }
                    }
                }

            }
        }

        return $html;
    }

    /**
     * @return string|null
     */
    private function renderAvailableGames()
    {
        $html = null;
        $testtime = null;
//        $usr = explode(' ', $this->user->name);
//        switch ($usr[0]) {
//            case 'Area':
//                $usr = substr($this->user->name, -1);
//                break;
//            case 'Section':
//                $pieces = explode(' ', $this->user->name);
//                $usr = array_pop($pieces);
//                break;
//            default:
//                $usr = null;
//
//        }

        $usr = $this->user->name;

        $rowColor = $this->colorDarkGray;

        $games = $this->sr->getGames($this->projectKey, $this->showgroup, true);

        if (count($games) == 0) {
            $html .= "<h3>No available matches.</h3>";
        } else {
            if ($this->num_unassigned) {
                $html .= "<h3 class='left'>Available matches :</h3>";
            }
            $html .= "<input type=\"hidden\" name=\"group\" value=\"$this->showgroup\">";

            $html .= "<table class=\"sched-table\" >\n";
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

            if (!$this->user->admin && !$this->event->archived && !$this->locked) {
                $html .= "<th>Assign to " . $this->user->name . "</th>";
            }
            $html .= "</tr>\n";

            $this->description = $this->user->name . ': Schedule';
            $gameCount = 0;
            foreach ($this->assigned_list['unassigned'] as $game) {
                if (($this->showgroup && $this->showgroup == $this->divisionAge(
                            $game->division
                        )) || !$this->showgroup) {
                    if ($this->user->admin || is_bool(strpos($game->home, $usr)) && is_bool(strpos($game->away, $usr))
                    ) {
                        $time = date('H:i', strtotime($game->time));

                        if (!$testtime) {
                            $testtime = $time;
                        } elseif ($testtime != $time) {
                            $testtime = $time;
                            switch ($rowColor) {
                                case $this->colorDarkGray:
                                    $rowColor = $this->colorLtGray;
                                    break;
                                default:
                                    $rowColor = $this->colorDarkGray;
                            }
                        }

                        switch ($rowColor) {
                            case $this->colorDarkGray:
                                $html .= "<tr class=\"center colorLtGray\">";
                                break;
                            default:
                                $html .= "<tr class=\"center colorDarkGray\">";
                        }
                        $html .= $this->renderGameNumber($game);
                        $html .= "<td>&nbsp;</td>";
                        if (!$this->user->admin && !$this->event->archived && !$this->locked) {
                            $html .= "<td><input type=\"checkbox\" name=\"assign:" . $game->id . "\" value=\""
                                . $game->id . "\"></td>";
                        }
                        $html .= "</tr>\n";

                        $gameCount++;
                    }
                }
            }

            if (empty($this->assigned_list['unassigned'])) {
                $html .= "<tr class=\"center\">";
                $html .= "<td colspan=\"10\" > No neutral assignments available. </td>";
                $html .= "</tr>\n";
            }

            $html .= "</table>\n";
        }

        return $html;
    }

    /**
     * @param $assignor
     * @return string|null
     * @throws Exception
     */
    private function renderAssignmentByArea(
        $assignor
    )
    {
        $html = null;
        $testtime = null;

        $gameCount = 0;

        $games = $this->sr->getGames($this->projectKey, $this->showgroup, true);

        foreach ($games as $game) {
            if ($assignor == $game->assignor) {
                $gameCount += 1;
            }
        }

        $strLastLogon = '';
        if ($this->user->admin && $gameCount > 0) {
            $lastLogon = $this->sr->getLastLogon($this->projectKey, $assignor);
            if (empty($lastLogon)) {
                $strLastLogon = " <span style=\"color:$this->colorAlert\">[Last logon: None]</span>";
            } else {
                $strLastLogon = " [Last logon: $lastLogon]";
            }
        }

        switch ($gameCount) {
            case 0:
                $html .= "<h3 class=\"left\">$gameCount Matches assigned to $assignor$strLastLogon</h3>\n";
                break;
            case 1:
                if (empty($this->assigned_list)) {
                    $html .= "<h3 class=\"left\">$gameCount Match Unassigned:</h3>\n";
                } else {
                    $html .= "<h3 class=\"left\">$gameCount Match assigned to $assignor$strLastLogon:</h3>\n";
                }
                break;
            default:
                if (empty($assignor)) {
                    $html .= "<h3 class=\"left\">$gameCount Matches Unassigned:</h3>\n";
                } else {
                    $html .= "<h3 class=\"left\">$gameCount Matches assigned to $assignor$strLastLogon:</h3>\n";
                }
        }
        $html .= "<div class=\"clear-fix\"></div>\n";

        if ($gameCount) {
            $html .= "<table class=\"sched-table\" >\n";
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
            if (empty(count($games))) {
                $html .= "<tr class=\"center colorHighlight\">";
                $html .= "<td colspan=\"9\">$assignor has no matches assigned</td>";
                $html .= "</tr>\n";
            } else {

                if (!$this->user->admin && !$this->event->archived) {
                    $html .= "<th>Assigned</th>";
                }
                $html .= "</tr>\n";

                $rowColor = $this->colorGroup1;

                foreach ($games as $game) {
                    if ($assignor == $game->assignor) {

                        if (!$testtime) {
                            $testtime = $game->time;
                        } elseif ($testtime != $game->time) {
                            $testtime = $game->time;
                            switch ($rowColor) {
                                case $this->colorGroup1:
                                    $rowColor = $this->colorGroup2;
                                    break;
                                default:
                                    $rowColor = $this->colorGroup1;
                            }
                        }

                        switch ($rowColor) {
                            case $this->colorGroup1:
                                $html .= "<tr class=\"center colorGroup2\">";
                                break;
                            default:
                                $rowColor = $this->colorGroup1;
                                $html .= "<tr class=\"center colorGroup1\">";
                        }
                        $html .= $this->renderGameNumber($game);
                        $html .= "<td>" . $game->assignor . "</td>";
                        if (!$this->user->admin) {
                            if ($this->locked) {
                                $html .= "<td>Locked</td>";
                            } elseif (!$this->event->archived) {
                                $html .= "<td><input name=\"matches:" . $game->id . "\" type=\"checkbox\" value=\""
                                    . $game->id . "\" checked></td>";
                            }
                        }
                        $html .= "</tr>\n";
                    }
                }

                $html .= "</table>";
            }
        }

        return $html;
    }

    private function renderGameNumber($game)
    {
        $date = date('D, d M', strtotime($game->date));
        $time = date('H:i', strtotime($game->time));

        if ($this->show_medal_round_divisions || !$game->medalRound || $this->user->admin) {
            $html = "<td>" . $game->game_number . "</td>";
        } else {
            $html = "<td></td>";
        }
        $html .= "<td>" . $date . "</td>";
        $html .= "<td>" . $time . "</td>";
        $field = $game->field;
        if ($this->show_medal_round_divisions || !$game->medalRound || $this->user->admin) {
            if (is_null($this->event->field_map)) {
                $html .= "<td>$field</td>";
            } else {
                $html .= "<td><a href='" . $this->event->field_map . "' target='_blank'>$field</a></td>";
            }
            $html .= "<td>$game->division</td>";
            $html .= "<td>" . $game->pool . "</td>";
            $html .= "<td>" . $game->home . "</td>";
            $html .= "<td>" . $game->away . "</td>";
        } else {
            $html .= "<td></td>";
            $html .= "<td></td>";
            $html .= "<td></td>";
            $html .= "<td></td>";
            $html .= "<td></td>";
        }

        return $html;
    }
}
