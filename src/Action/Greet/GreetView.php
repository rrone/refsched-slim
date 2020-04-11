<?php

namespace App\Action\Greet;


use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractView;
use App\Action\SchedulerRepository;


class GreetView extends AbstractView
{
    private $games;
    private $description;
    private $userview;

    public function __construct(Container $container, SchedulerRepository $schedulerRepository)
    {
        parent::__construct($container, $schedulerRepository);

        $this->games = null;
        $this->description = 'No matches scheduled';
    }

    /**
     * @param Request $request
     * @param Response $response
     */
    public function handler(Request $request, Response $response)
    {
        $this->user = $request->getAttribute('user');
        $this->event = $request->getAttribute('event');

        $this->userview = $_SESSION['view'] == 'asuser';

    }

    /**
     * @param Response $response
     *
     */
    public function render(Response &$response)
    {
        $html = $this->renderView();

        $content = array(
            'view' => array(
                'admin' => $this->user->admin,
                'content' => $html,
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
    protected function renderView()
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

            $show_medal_round = $this->sr->getMedalRound($projectKey);
            $show_medal_round_details = $this->sr->getMedalRoundDivisions($projectKey);
            $show_medal_round_assignments = $this->sr->getMedalRoundAssignedNames($projectKey);

            $locked = $this->sr->getLocked($projectKey);

            $this->games = $this->sr->getGames($projectKey, '%', $show_medal_round);

            $this->description = "Welcome ";
            if ($this->user->admin) {
                $this->description .= $this->user->name;
            } else {
                $this->description .= $this->user->name." Assignor";
            }

            $html .= "<h3 class=\"center\" style=\"color:$this->colorAlert\">CURRENT STATUS</h3>\n<h3 class=\"center\">";
            $html .= "<h3 class=\"center\">";

            if ($this->user->admin) {
                if ($locked) {
                    $html .= "The schedule is:&nbsp;<span style=\"color:$this->colorAlert\">Locked</span>&nbsp;-&nbsp;(<a href=".$this->getBaseURL(
                            'unlockPath'
                        ).">Unlock</a> the schedule now)<br><br>\n";
                } else {
                    $html .= "The schedule is:&nbsp;<span style=\"color:$this->colorSuccess\">Unlocked</span>&nbsp;-&nbsp;(<a href=".$this->getBaseURL(
                            'lockPath'
                        ).">Lock</a> the schedule now)<br><br>\n";
                }
                if ($show_medal_round) {
                    $html .= "Medal round matches are:&nbsp;<span style=\"color:$this->colorSuccess\">Viewable</span>&nbsp;-&nbsp;(<a href=".$this->getBaseURL(
                            'hideMRPath'
                        ).">Hide Medal Round matches</a> from users)<br><br>\n";
                } else {
                    $html .= "Medal round matches are:&nbsp;<span style=\"color:$this->colorAlert\">Not Viewable</span>&nbsp;-&nbsp;(<a href=".$this->getBaseURL(
                            'showMRPath'
                        ).">Show Medal Round matches</a> to users)<br><br>\n";
                }
                if ($show_medal_round_details) {
                    $html .= "Medal round details are:&nbsp;<span style=\"color:$this->colorSuccess\">Viewable</span>&nbsp;-&nbsp;(<a href=".$this->getBaseURL(
                            'hideMRDivPath'
                        ).">Hide Medal Round details</a> from users)<br><br>\n";
                } else {
                    $html .= "Medal round details are:&nbsp;<span style=\"color:$this->colorAlert\">Not Viewable</span>&nbsp;-&nbsp;(<a href=".$this->getBaseURL(
                            'showMRDivPath'
                        ).">Show Medal Round details</a> to users)<br><br>\n";
                }
                if ($show_medal_round_assignments) {
                    $html .= "Medal round referee assignments are&nbsp;<span style=\"color:$this->colorSuccess\">Assigned</span>&nbsp;-&nbsp;(<a href=".$this->getBaseURL(
                            'hideMRAssignmentsPath'
                        ).">Show as Placeholders)</a><br><br>\n";
                } else {
                    $html .= "Medal round referee assignments are&nbsp;<span style=\"color:$this->colorAlert\">Placeholders</span>&nbsp;-&nbsp;(<a href=".$this->getBaseURL(
                            'showMRAssignmentsPath'
                        ).">Show Assignments)</a><br><br>\n";
                }
            }

            if (count($this->games)) {
                $used_list = [];
                $assigned_list = [];
                $limit_list = [];

                $groups = $this->sr->getGroups($projectKey);

                foreach ($groups as $group) {
                    $used_list[$group] = 0;
                    $assigned_list[$group] = 0;
                    $limit_list[$group] = 'none';
                }

                $limits = $this->sr->getLimits($projectKey);
                foreach ($limits as $group) {
                    if (($group->division != 'none') && !empty($group->division)) {
                        $limit_list[$group->division] = $group->limit;
                    }
                }

                $delim = ' - ';
                $num_assigned = 0;
                $num_area = 0;
                $allatlimit = true;

                foreach ($this->games as $game) {
                    if ($this->user->admin && !empty($game->assignor)) {
                        $num_assigned++;
                    } elseif ($this->user->name == $game->assignor) {
                        $num_area++;
                        $assigned_list[$this->divisionAge($game->division)]++;
                    }
                    $used_list[$this->divisionAge($game->division)] = 1;
                }
                $num_unassigned = count($this->games) - $num_assigned;

                $uname = $this->user->name;

                if (!$this->user->admin) {

                    if ($locked) {
                        $html .= "The schedule is presently <span style=\"color:$this->colorAlert\">locked</span><br><br>\n";
                    } else {
                        $html .= "The schedule is presently <span style=\"color:$this->colorSuccess\">unlocked</span><br><br>\n";
                    }
                }

                if ($this->user->admin) {

                    //get the grammar right
                    if ($num_assigned == 1 && $num_unassigned == 1) {
                        $html .= "<span style=\"color:#008800\">$num_assigned</span> match is assigned and <span style=\"color:$this->colorAlert\">$num_unassigned</span> is unassigned<br>\n";
                    } elseif ($num_assigned > 1 && $num_unassigned == 1) {
                        $html .= "<span style=\"color:#008800\">$num_assigned</span> matches are assigned and <span style=\"color:$this->colorAlert\">$num_unassigned</span> is unassigned<br>\n";
                    } elseif ($num_assigned == 1 && $num_unassigned > 1) {
                        $html .= "<span style=\"color:#008800\">$num_assigned</span> match is assigned and <span style=\"color:$this->colorAlert\">$num_unassigned</span> are unassigned<br>\n";
                    } else {
                        $html .= "<span style=\"color:#008800\">$num_assigned</span> matches are assigned and <span style=\"color:$this->colorAlert\">$num_unassigned</span> are unassigned<br>\n";
                    }

                    if (count($limit_list) == 0) {
                        $html .= "There is <span style=\"color:$this->colorWarning\">no</span> match limit at this time<br>\n";
                    } else {
                        if (array_key_exists('all', $limit_list)) {
                            $tmplimit = $limit_list['all'];
                            if ($tmplimit != 'none') {
                                $html .= "There is a <span style=\"color:$this->colorWarning\">$tmplimit</span> match limit in all divisions<br>\n";
                            } else {
                                $html .= "There is <span style=\"color:$this->colorWarning\">no</span> match limit at this time<br>\n";
                            }
                        }
                    }
                } else {
                    if ($num_area == 0) {
                        $html .= "$uname is not currently assigned to any matches.<br>";
                    } elseif ($num_area == 1) {
                        $html .= "$uname is currently assigned to <span style=\"color:$this->colorSuccess\">$num_area</span> match.<br><br>";
                    } else {
                        $html .= "$uname is currently assigned to <span style=\"color:$this->colorSuccess\">$num_area</span> matches.<br><br>";
                    }

                    if (count($limit_list) == 0) {
                        $html .= "There is <span style=\"color:$this->colorWarning\">no</span> match limit at this time<br>\n";
                    } else {
                        if (array_key_exists('all', $limit_list)) {
                            $tmplimit = $limit_list['all'];
                            if (in_array($tmplimit, ['999', 'none'])) {
                                $html .= "There is <span style=\"color:$this->colorWarning\">no</span> limit of Area assigned matches at this time<br>\n";
                            }
                        } else {
                            foreach ($limit_list as $k => $v) {
                                $tmpassigned = $assigned_list[$k];
                                if ($used_list[$k]) {
                                    if ($limit_list[$k] == 'none') {
                                        $html .= "You have taken <span style=\"color:$this->colorWarning\">$tmpassigned</span> $k matches.  There is <span style=\"color:$this->colorWarning\">no</span> match limit for $k.<br>\n";
                                        $allatlimit = false;
                                    } else {
                                        $html .= "You have taken <span style=\"color:$this->colorWarning\">$tmpassigned</span> matches of your <span style=\"color:$this->colorWarning\">$v</span> match limit for $k<br>\n";
                                        $allatlimit &= $tmpassigned >= $v;
                                    }
                                }
                            }
                            if (count($assigned_list) < count($used_list)) {
                                $html .= "There is <span style=\"color:$this->colorWarning\">no</span> match limit for all other divisions<br>\n";
                            }
                        }
                    }

                    if ($locked && !array_key_exists('none', $limit_list)) {
                        if (!$allatlimit) {
                            $html .= "<br>You may sign ".$this->user->name." teams up for matches but you may not remove them</h3>\n";
                        } else {
                            $html .= "<br>Since ".$this->user->name." is at or above your limit, you will not be able to sign teams up for matches</h3>\n";
                        }
                    }
                }
                $html .= "</h3>";

                if ($this->user->admin) {
                    $html .= "<h3 class=\"center\" style=\"color:$this->colorAlert\">VIEWING OPTIONS</h3>\n";
                    if ($this->userview) {
                        $html .= "<h3 class=\"center\"><a  style='font-weight: lighter' href="
                            .$this->getBaseURL(
                                'greetPath'
                            ).'?asadmin'.">View as Admin</a> - ";
                        $html .= "<a href=".$this->getBaseURL(
                                'greetPath'
                            ).'?asuser'.">Viewing as User</a></h3>";
                    } else {
                        $html .= "<h3 class=\"center\"><a style='font-weight: lighter' href="
                            .$this->getBaseURL(
                                'greetPath'
                            ).'?asadmin'.">Viewing as Admin</a> - ";
                        $html .= "<a href=".$this->getBaseURL(
                                'greetPath'
                            ).'?asuser'.">View as User</a></h3>";
                    }
                }

                $html .= "<hr class=\"center width50\">";

                $html .= "<h3 class=\"center\" style=\"color:$this->colorAlert\">ACTIONS</h3>\n";

                $html .= "<h3 class=\"center\"><a href=".$this->getBaseURL(
                        'fullPath'
                    ).">View full schedule</a></h3>";
                if ($this->user->admin) {
                    if (!$this->event->archived) {
                        $html .= "<h3 class=\"center\"><a href=".$this->getBaseURL(
                                'editGamePath'
                            ).">Edit matches</a></h3>";
                    }
                    $html .= "<h3 class=\"center\"><a href=".$this->getBaseURL(
                            'schedPath'
                        ).">View Match Assignors</a></h3>";
                    $html .= "<h3 class=\"center\"><a href=".$this->getBaseURL(
                            'masterPath'
                        ).">Select Match Assignors</a></h3>";
                    $html .= "<h3 class=\"center\"><a href=".$this->getBaseURL(
                            'refsPath'
                        ).">Edit Referee Assignments</a></h3>";

                    if ($this->user->admin) {
                        $html .= "<h3 class=\"center\"><a href=".$this->getBaseURL(
                                'adminPath'
                            ).">Admin Functions</a></h3>";
                    }
                } else {
                    $html .= "<h3 class=\"center\">Goto $uname Schedule: <a href=".$this->getBaseURL(
                            'schedPath'
                        ).">All matches</a> - ";
                    foreach ($groups as $group) {
                        $html .= "<a href=\"".$this->getBaseURL('schedPath')."?group=$group\">$group</a>".$delim;
                    }
                    $html = substr($html, 0, strlen($html) - 3)."</h3>";
                    $html .= "<h3 class=\"center\"><a href=".$this->getBaseURL(
                            'refsPath'
                        ).">Edit $uname Referee Assignments</a></h3>";

                }
            } else {
                $html .= "<h3 class=\"center\">There are no matches to schedule</h3>";
//                $html .= "<br><p><a class='info' id='Rick Roberts' href='#'>Rick Roberts</a>";
            }

            $html .= "<h3 class=\"center\"><a href=".$this->getBaseURL('endPath').">Log Off</a></h3>";

        }

        return $html;

    }
}