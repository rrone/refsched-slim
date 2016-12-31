<?php
namespace App\Action\Sched;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\SchedulerRepository;
use App\Action\AbstractView;

class SchedSchedView extends AbstractView
{
    private $showgroup;

    private $num_assigned;
    private $num_unassigned;

    private $game_id = [];
    private $game_no = [];
    private $date = [];
    private $field = [];
    private $time = [];
    private $div = [];
    private $pool = [];
    private $home = [];
    private $away = [];
    private $ref_team = [];

    public function __construct(Container $container, SchedulerRepository $schedulerRepository)
    {
        parent::__construct($container, $schedulerRepository);

        $this->showgroup = null;
    }

    public function handler(Request $request, Response $response)
    {
        $this->user = $request->getAttribute('user');
        $this->event = $request->getAttribute('event');

        if ($request->isPost() && !$this->isRepost($request)) {
            $projectKey = $this->event->projectKey;
            $locked = $this->sr->getLocked($projectKey);
            $show_medal_round = $this->sr->getMedalRound($projectKey);

            $this->msg = null;
            $limit_list = [];

            //load limits if any or none
            $limits = $this->sr->getLimits($projectKey);
            $no_limit = false;
            if (!count($limits)) {
                $no_limit = true;
            } else {
                foreach ($limits as $group) {
                    $limit_list[$group->division] = $group->limit;
                }
            }

            $_POST = $request->getParsedBody();
            $array_of_keys = array_keys($_POST);

            //parse the POST data
            $this->showgroup = !empty($_POST['group']) ? $_POST['group'] : null;

            $adds = [];
            $assign = [];
            foreach ($array_of_keys as $key) {
                $change = explode(':', $key);
                switch ($change[0]) {
                    case 'assign':
                        $adds[$change[1]] = $this->user->name;
                        break;
                    case 'games':
                        $assign[$change[1]] = $this->user->name;
                        break;
                    default:
                        continue;
                }
            }

            if (!$locked) {
                //remove drops if not locked
                $assigned_games = $this->sr->getGamesByRep($projectKey, $this->user->name, $show_medal_round);

                if (count($assign) != count($assigned_games)) {
                    $removed = [];
                    $unassign = [];
                    foreach ($assigned_games as $game) {
                        if (!in_array($game->id, array_keys($assign))) {
                            if (is_null($this->showgroup) || ($this->showgroup == $this->divisionAge($game->division))) {
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
                                $this->msg .= "<p>You have <strong>removed</strong> your referee team from $game->division Game# $game->game_number on $game->date at $game->time on $game->field</p>";
                            }
                        }
                    }

                    $this->sr->updateAssignor($unassign);
                }
            }

            //initialize counting groups
            $assigned_games = $this->sr->getGamesByRep($projectKey, $this->user->name, $show_medal_round);
            foreach ($assigned_games as $game) {
                $div = $this->divisionAge($game->division);
                if (!isset($games_now[$div])) {
                    $games_now[$div] = 0;
                }
                $games_now[$div]++;
            }

            if (count($adds)) {
                //Update based on add/returned games
                $added = [];
                $unavailable = [];
                $games = $this->sr->getGames($projectKey, '%', $show_medal_round);
                foreach ($games as $game) {
                    $date = date('D, d M', strtotime($game->date));
                    $time = date('H:i', strtotime($game->time));
                    $div = $this->divisionAge($game->division);
                    //ensure all indexes exist
                    $games_now[$div] = isset($games_now[$div]) ? $games_now[$div] : 0;
                    $atLimit[$div] = isset($atLimit[$div]) ? $atLimit[$div] : 0;
                    //if requested

                    if (in_array($game->id, array_keys($adds))) {
                        //and available
                        if (empty($game->assignor)) {
                            //and below the limit if there is one

                            if (!isset($limit_list[$div]) || $games_now[$div] < $limit_list[$div] || $no_limit) {
                                //make the assignment
                                $data = [$game->id => $this->user->name];
                                $this->sr->updateAssignor($data);
                                $added[$game->id] = $game;
                                $games_now[$div]++;
                                $this->msg .= "<p>You have <strong>scheduled</strong> $game->division Game# $game->game_number on $date on $game->field at $time</p>";
                            } else {
                                $atLimit[$div]++;
                                $this->msg .= "<p>You have <strong>not scheduled</strong> $game->division Game# $game->game_number on $date on $game->field at $time because you are at your game limit!</p>";
                            }
                        } else {
                            $unavailable[$game->id] = $game;
                            $this->msg = "<p>Sorry, $game->division Game# $game->game_number has been scheduled by $game->assignor</p>";
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

    public function render(Response &$response)
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
                'description' => $this->user->name . ' Schedule',
                'message' => $this->msg,
            )
        );

        $this->view->render($response, 'sched.html.twig', $content);

    }

    private function renderView()
    {
        $html = null;

        if (!empty($this->event)) {
            $projectKey = $this->event->projectKey;

            $allatlimit = true;
            $oneatlimit = false;
            $showavailable = false;
            $a_init = substr($this->user->name, -1);
            $assigned_list = [];
            $limit_list = [];

            if (!$this->user->admin) {
                $groups = $this->sr->getGroups($projectKey);
                foreach ($groups as $group) {
                    $assigned_list[$group] = 0;
                    $limit_list[$group] = 'none';
                }

                $limits = $this->sr->getLimits($projectKey);
                foreach ($limits as $group) {
                    $limit_list[$group->division] = $group->limit;
                }
                if (!count($limit_list)) {
                    $limit_list['none'] = 1;
                }
            } else {
                $limit_list['none'] = 1;
            }

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

            $kount = 0;
            $testtime = null;

            $locked = $this->sr->getLocked($projectKey);
            $show_medal_round = $this->sr->getMedalRound($projectKey);

            $_SESSION['locked'] = $locked;

            $games = $this->sr->getGames($projectKey, $this->showgroup, $this->user->admin || $show_medal_round);
            $this->num_assigned = 0;
            $this->num_unassigned = count($games);

            foreach ($games as $game) {
                $this->game_id[] = $game->id;
                $this->game_no[] = $game->game_number;
                $this->date[] = date('D, d M', strtotime($game->date));
                $this->field[] = $game->field;
                $this->time[] = date('H:i', strtotime($game->time));
                $this->div[] = $game->division;
                $this->pool[] = $game->pool;
                $this->home[] = $game->home;
                $this->away[] = $game->away;
                $this->ref_team[] = $game->assignor;
                if (!empty($game->assignor)) {
                    $this->num_unassigned--;
                }
                if ($game->assignor == $this->user->name) {
                    $this->num_assigned++;
                    if (isset($assigned_list[$this->divisionAge($game->division)])) {
                        $assigned_list[$this->divisionAge($game->division)]++;
                    } else {
                        $assigned_list[$this->divisionAge($game->division)] = 1;
                    }
                }
                $cr[] = $game->cr;
                $ar1[] = $game->ar1;
                $ar2[] = $game->ar2;
                $r4th[] = $game->r4th;

                $kount = count($games);
            }

            if ($locked && array_key_exists('none', $limit_list)) {
                $html .= "<h3 class=\"center\"><span style=\"color:$this->colorAlert\">The schedule is locked<br>";
                if (!$this->user->admin) {
                    $html .= "You may sign up for games but not unassign yourself";
                }
                $html .= "</span></h3>\n";

                $allatlimit = false;
                $showavailable = true;
            } elseif ($locked && array_key_exists('all', $limit_list) && $this->num_assigned < $limit_list['all']) {
                $html .= "<h3 class=\"center\"><span style=\"color:$this->colorAlert\">The schedule is locked<br>";
                if (!$this->user->admin) {
                    $html .= "You may sign up for games but not unassign yourself";
                }
                $html .= "</span></h3>\n";

                $allatlimit = false;
                $showavailable = true;
            } elseif ($locked && array_key_exists('all', $limit_list) && $this->num_assigned == $limit_list['all']) {
                $html .= "<h3 class=\"center\"><span style=\"color:$this->colorAlert\">The schedule is locked and you are at your game limit<br>\nYou will not be able to unassign yourself from games to sign up for others<br>\nThe submit button on this page has been disabled and available games are not shown<br>\nYou probably want to <a href=" . $$this->getBaseURL('greetPath') . ">Go to the Main Page</a> or <a href=" . $this->getBaseURL('endPath') . ">Log Off</a></span></h3>\n";
            } elseif ($locked && array_key_exists('all', $limit_list) && $this->num_assigned > $limit_list['all']) {
                $html .= "<h3 class=\"center\"><span style=\"color:$this->colorAlert\">The schedule is locked and you are above your game limit<br>\nThe extra games were probably assigned by the Section staff<br>\nYou will not be able to unassign yourself from games to sign up for others<br>\nThe Submit button has been disabled and available games are not shown<br>\nYou probably want to <a href=" . $this->getBaseURL('greetPath') . ">Go to the Main Page</a> or <a href=" . $this->getBaseURL('endPath') . ">Log Off</a></span></h3>\n";
            } elseif (!$locked && array_key_exists('all', $limit_list) && $this->num_assigned < $limit_list['all']) {
                $tmplimit = $limit_list['all'];
                $html .= "<h3 class=\"center\">You are currently assigned to <span style=\"color:$this->colorAlert\">$this->num_assigned</span> of your <span style=\"color:$this->colorAlert\">$tmplimit</span> games</h3>\n";
                $showavailable = true;
            } elseif (!$locked && array_key_exists('all', $limit_list) && $this->num_assigned == $limit_list['all']) {
                $html .= "<h3 class=\"center\"><span style=\"color:$this->colorAlert\">You are at your game limit<br>You will have to unassign yourself from games to sign up for others</span></h3>\n";
            } elseif (!$locked && array_key_exists('all', $limit_list) && $this->num_assigned > $limit_list['all']) {
                $html .= "<h3 class=\"center\"><span style=\"color:$this->colorAlert\">You are above your game limit<br>\nThe extra games were probably assigned by the Section staff<br>\nIf you continue from here you will not be able to keep all the games you are signed up for and may lose some of the games you already have<br>\nIf you want to keep these games and remain over the game limit it is recommended that you do not hit submit but do something else instead<br>\n<a href=" . $this->getBaseURL('greetPath') . ">Go to the Main Page</a></span></h3>\n";
            } elseif ($locked && count($limit_list)) {
                $html .= "<h3 class=\"center\"><span style=\"color:$this->colorAlert\">The schedule is locked<br>";
                if ($showavailable) {
                    $html .= "<br>You can add games to divisions that are below the limit but not unassign your Area from games<br>";
                }
                $html .= "</span><br>\n";
                foreach ($assigned_list as $k => $v) {
                    $tempassign = $assigned_list[$k];
                    if (!$this->showgroup || $this->showgroup == $k) {
                        if (isset($limit_list[$k]) && $limit_list[$k] != 'none') {
                            $html .= "For $k, you are assigned to <span style=\"color:$this->colorAlert\">$tempassign</span> games with a limit of <span style=\"color:$this->colorAlert\">$limit_list[$k]</span> games<br>\n";
                        } else {
                            $html .= "For $k, you are assigned to <span style=\"color:$this->colorAlert\">$tempassign</span> games with no limit<br>\n";
                        }
                    }

                    $showavailable = ($assigned_list[$k] < $limit_list[$k]) || !isset($limit_list[$k]) || $limit_list[$k] == 'none';
                }

                $html .= "</h3>\n";
            } elseif (!$locked && count($limit_list)) {
                $html .= "<h3 class=\"center\">\n";
                foreach ($assigned_list as $k => $v) {
                    $tempassign = $assigned_list[$k];
                    if (!$this->showgroup || $this->showgroup == $k) {
                        if (isset($limit_list[$k]) && $limit_list[$k] != 'none') {
                            $html .= "For $k, you are assigned to <span style=\"color:$this->colorAlert\">$tempassign</span> games with a limit of <span style=\"color:$this->colorAlert\">$limit_list[$k]</span> games<br>\n";
                            $oneatlimit = ($assigned_list[$k] >= $limit_list[$k]);
                        } else {
                            $html .= "For $k, you are assigned to <span style=\"color:$this->colorAlert\">$tempassign</span> games with no limit<br>\n";
                            $oneatlimit = false;
                        }
                    }
                }
                if ($oneatlimit) {
                    $html .= "<br><span style=\"color:$this->colorAlert\">One or more of your divisions are at or above their limits<br>You will need to unassign games in that division before you can select additional games</span>\n";
                }
                $html .= "</h3>\n";
                $showavailable = true;
            }

            $rowColor = $this->colorDarkGray;

            if ($this->num_assigned || ($showavailable && $this->num_unassigned) || $this->user->admin) {
                $submitDisabled = (!$locked && (!$allatlimit && !empty($assigned_list)) || $showavailable) ? '' : ' disabled';

                $html .= "<form name=\"form1\" method=\"post\" action=" . $this->getBaseURL('schedPath') . ">\n";

                $html .= "<div class=\"center\">";

                if (!$this->user->admin && (($showavailable && $this->num_unassigned) || $this->num_assigned) || $this->user->admin) {
                    $html .= "<h3 class=\"h3-btn center\" >";
                    $html .= $this->menuLinks();
                    if(!$this->user->admin) {
                        $html .= "<input class=\"btn btn-primary btn-xs right $submitDisabled\" type=\"submit\" name=\"Submit\" value=\"Submit\">";
                    }
                    $html .= "<div class='clear-fix'></div>\n";
                    $html .= "<h3>";
                }

                $html .= "<h3 class=\"center\"> Shading change indicates different start times</h3>\n";

                if ($showavailable && $this->num_unassigned) {
                    $html .= "<h3 class='left'>Available games :</h3>";
                }
                $html .= "<input type=\"hidden\" name=\"group\" value=\"$this->showgroup\">";

                if ($this->num_unassigned) {
                    $html .= "<table class=\"sched-table\" >\n";
                    $html .= "<tr class=\"center\" bgcolor=\"$this->colorTitle\">";
                    $html .= "<th>Game No</th>";
                    $html .= "<th>Date</th>";
                    $html .= "<th>Time</th>";
                    $html .= "<th>Field</th>";
                    $html .= "<th>Division</th>";
                    $html .= "<th>Pool</th>";
                    $html .= "<th>Home</th>";
                    $html .= "<th>Away</th>";
                    $html .= "<th>Referee Team</th>";
                    if (!$this->user->admin) {
                        $html .= "<th>Assign to " . $this->user->name . "</th>";
                    }
                    $html .= "</tr>\n";
                    $wasHTML = $html;
                    for ($kant = 0; $kant < $kount; $kant++) {
                        if (($this->showgroup && $this->showgroup == $this->divisionAge($this->div[$kant])) || !$this->showgroup) {
                            if ($a_init != substr($this->home[$kant], 0, 1) && $a_init != substr($this->away[$kant], 0, 1) && !$this->ref_team[$kant] && $showavailable) {
                                if (!$testtime) {
                                    $testtime = $this->time[$kant];
                                } elseif ($testtime != $this->time[$kant]) {
                                    $testtime = $this->time[$kant];
                                    switch ($rowColor) {
                                        case $this->colorDarkGray:
                                            $rowColor = $this->colorLtGray;
                                            break;
                                        default:
                                            $rowColor = $this->colorDarkGray;
                                    }
                                }

                                $html .= "<tr class=\"center\" bgcolor=\"$rowColor\">";
                                $html .= "<td>" . $this->game_no[$kant] . "</td>";
                                $html .= "<td>" . $this->date[$kant] . "</td>";
                                $html .= "<td>" . $this->time[$kant] . "</td>";
                                $html .= "<td>" . $this->field[$kant] . "</td>";
                                $html .= "<td>" . $this->div[$kant] . "</td>";
                                $html .= "<td>" . $this->pool[$kant] . "</td>";
                                $html .= "<td>" . $this->home[$kant] . "</td>";
                                $html .= "<td>" . $this->away[$kant] . "</td>";
                                $html .= "<td>&nbsp;</td>";
                                if (!$this->user->admin) {
                                    $html .= "<td><input type=\"checkbox\" name=\"assign:" . $this->game_id[$kant] . "\" value=\"" . $this->game_id[$kant] . "\"></td>";
                                }
                                $html .= "</tr>\n";
                            }
                        }
                    }
                    if ($html == $wasHTML) {
                        $html .= "<tr class=\"center\">";
                        $html .= "<td colspan=\"10\" > No neutral assignments available. </td>";
                        $html .= "</tr>\n";
                    }

                    $html .= "</table>\n";
                }

                if (!$this->user->admin) {
                    if ($this->num_assigned) {
                        $html .= $this->renderAssignmentByArea($this->user, $kount, $locked, true);
                    }
                } else {
                    $users = $this->sr->getUsers();

                    foreach ($users as $user) {
                        $html .= $this->renderAssignmentByArea($user, $kount, $locked, false);
                    }
                }

                if (!$this->user->admin && (($showavailable && $this->num_unassigned) || $this->num_assigned) || $this->user->admin) {
                    $html .= "<h3 class=\"h3-btn center\" >";
                    $html .= $this->menuLinks();
                    if(!$this->user->admin) {
                        $html .= "<input class=\"btn btn-primary btn-xs right $submitDisabled\" type=\"submit\" name=\"Submit\" value=\"Submit\">";
                    }
                    $html .= "<div class='clear-fix'></div>";
                    $html .= "<h3>\n";
                }

                $html .= "</form>\n";

            } else {
//                $html .= "<h3 class=\"center\">You have no games assigned.</h3>\n";
                $html .= "<h3 class=\"h3-btn center\" >";
                $html .= $this->menuLinks();
                $html .= "<h3>\n";
            }

            $_SESSION['locked'] = $locked;

        }

        return $html;

    }

//    private function menu()
//    {
//        $html = "<h3 class=\"center\">";
//
//        $html .= $this->menuLinks();
//
//        $html .= "</h3>\n";
//
//        return $html;
//    }

    private function menuLinks()
    {
        $uname = $this->user->name;

        $html = "<a href=" . $this->getBaseURL('greetPath') . ">Home</a>&nbsp;-&nbsp;";
        $html .= "<a href=" . $this->getBaseURL('fullPath') . ">View the full schedule</a>&nbsp;-&nbsp;";

        if ($this->user->admin) {
            $html .= "<a href=" . $this->getBaseURL('editGamePath') . ">Edit games</a>&nbsp;-&nbsp;";
            $html .= "<a href=" . $this->getBaseURL('masterPath') . ">Select Assignors</a>&nbsp;-&nbsp;";
            $html .= "<a href=" . $this->getBaseURL('refsPath') . ">Edit referee assignments</a>&nbsp;-&nbsp;";
        } elseif ($this->showgroup) {
            $html .= "<a href=" . $this->getBaseURL('schedPath') . ">View all $uname games</a>&nbsp;-&nbsp;";
        } elseif ($this->num_assigned) {
            $html .= "<a href=" . $this->getBaseURL('refsPath') . ">Edit $uname referee assignments</a>&nbsp;-&nbsp;";
        }

        $html .= "<a href=" . $this->getBaseURL('endPath') . ">Log off</a>";

        return $html;
    }

    private function renderAssignmentByArea($user, $kount, $locked, $checkbox = true)
    {
        $html = null;
        $testtime = null;

        $assigned = in_array($user->name, $this->ref_team);
        if (!$assigned) {
            $html .= "<h3 class=\"left\">$this->showgroup Games assigned to $user->name: <span style=\"color:$this->colorAlert\">NONE</span></h3><br>\n";
            $html .= "<div class=\"clear-fix\"></div>\n";
            return $html;
        }

        $html .= "<h3 class=\"left\">$this->showgroup Games assigned to $user->name :</h3>\n";
        $html .= "<div class=\"clear-fix\"></div>\n";

        if (empty($kount)) {
            $html .= "<table class=\"sched-table\" >\n";
            $html .= "<tr class=\"center\" bgcolor=\"$this->colorHighlight\">";
            $html .= "<td>$user->name has no games assigned</td>";
            $html .= "</tr>\n";
        } else {
            $html .= "<table class=\"sched-table\" >\n";
            $html .= "<tr class=\"center\" bgcolor=\"$this->colorTitle\">";
            $html .= "<th>Game No</th>";
            $html .= "<th>Date</th>";
            $html .= "<th>Time</th>";
            $html .= "<th>Field</th>";
            $html .= "<th>Division</th>";
            $html .= "<th>Pool</th>";
            $html .= "<th>Home</th>";
            $html .= "<th>Away</th>";
            $html .= "<th>Referee Team</th>";
            if ($checkbox) {
                $html .= "<th>Assigned</th>";
            }
            $html .= "</tr>\n";

            $rowColor = $this->colorGroup1;

            for ($kant = 0; $kant < $kount; $kant++) {
                if ($user->name == $this->ref_team[$kant]) {

                    if (!$testtime) {
                        $testtime = $this->time[$kant];
                    } elseif ($testtime != $this->time[$kant]) {
                        $testtime = $this->time[$kant];
                        switch ($rowColor) {
                            case $this->colorGroup1:
                                $rowColor = $this->colorGroup2;
                                break;
                            default:
                                $rowColor = $this->colorGroup1;
                        }
                    }

                    $html .= "<tr class=\"center\" bgcolor=\"$rowColor\">";
                    $html .= "<td>" . $this->game_no[$kant] . "</td>";
                    $html .= "<td>" . $this->date[$kant] . "</td>";
                    $html .= "<td>" . $this->time[$kant] . "</td>";
                    $html .= "<td>" . $this->field[$kant] . "</td>";
                    $html .= "<td>" . $this->div[$kant] . "</td>";
                    $html .= "<td>" . $this->pool[$kant] . "</td>";
                    $html .= "<td>" . $this->home[$kant] . "</td>";
                    $html .= "<td>" . $this->away[$kant] . "</td>";
                    $html .= "<td>" . $this->ref_team[$kant] . "</td>";
                    if ($checkbox) {
                        if ($locked) {
                            $html .= "<td>Locked</td>";
                        } else {
                            $html .= "<td><input name=\"games:" . $this->game_id[$kant] . "\" type=\"checkbox\" value=\"" . $this->game_id[$kant] . "\" checked></td>";
                        }
                    }
                    $html .= "</tr>\n";
                }
            }
            $html .= "</table>";
        }

        return $html;
    }
}