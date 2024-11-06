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
    private string $description;

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
    }

    /**
     * @param Response $response
     *
     */
    public function render(Response $response)
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
    protected function renderView(): ?string
    {
        $html = null;

        if (!empty($this->event)) {
            $this->projectKey = $this->event->projectKey;
            //refresh event
            $this->event = $this->sr->getEvent($this->projectKey);

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

            $show_medal_round = $this->sr->getMedalRound($this->projectKey);
            $show_medal_round_divisions = $this->sr->getMedalRoundDivisions($this->projectKey);

            $locked = $this->sr->getLocked($this->projectKey);

            $this->games = $this->sr->getGames($this->projectKey, '%', $show_medal_round || $this->user->admin);

            $this->description = "Welcome ";
            if ($this->user->admin) {
                $this->description .= $this->user->name;
            } else {
                $this->description .= $this->user->name . " Assignor";
            }

            $html .= "<h3 class=\"center\" style=\"color:$this->colorAlert\">CURRENT STATUS</h3>\n<h3 class=\"center\">";
            $html .= "<h3 class=\"center\">";

            if ($this->user->admin) {
                if ($locked) {
                    $html .= "Assignments are:&nbsp;<span style=\"color:$this->colorAlert\">Locked</span>&nbsp;&nbsp;(<a href=" . $this->getBaseURL(
                            'unlockPath'
                        ) . ">Unlock</a> assignments now)<br><br>\n";
                } else {
                    $html .= "Assignments are:&nbsp;<span style=\"color:$this->colorSuccess\">Unlocked</span>&nbsp;&nbsp;(<a href=" . $this->getBaseURL(
                            'lockPath'
                        ) . ">Lock</a> assignments now)<br><br>\n";
                }
                if ($show_medal_round) {
                    $html .= "Medal round assignments are:&nbsp;<span style=\"color:$this->colorSuccess\">Viewable</span>&nbsp;&nbsp;(<a href=" . $this->getBaseURL(
                            'hideMRPath'
                        ) . ">Hide Medal Round Assignments</a> from users)<br><br>\n";
                } else {
                    $html .= "Medal round assignments are:&nbsp;<span style=\"color:$this->colorAlert\">Not Viewable</span>&nbsp;&nbsp;(<a href=" . $this->getBaseURL(
                            'showMRPath'
                        ) . ">Show Medal Round Assignments</a> to users)<br><br>\n";
                }
                if ($show_medal_round_divisions) {
                    $html .= "Medal round divisions are:&nbsp;<span style=\"color:$this->colorSuccess\">Viewable</span>&nbsp;&nbsp;(<a href=" . $this->getBaseURL(
                            'hideMRDivPath'
                        ) . ">Hide Medal Round Divisions</a> from users)<br><br>\n";
                } else {
                    $html .= "Medal round divisions are:&nbsp;<span style=\"color:$this->colorAlert\">Not Viewable</span>&nbsp;&nbsp;(<a href=" . $this->getBaseURL(
                            'showMRDivPath'
                        ) . ">Show Medal Round Divisions</a> to users)<br><br>\n";
                }
            }

            if (count($this->games) || $this->user->admin) {
                $used_list = [];
                $delim = ' - ';
                $num_assigned = 0;
                $num_area = 0;
                $all_at_limit = false;

                $this->initLimitLists();

                foreach ($this->games as $game) {
                    $game->division = explode(' ', $game->division)[0];
                    if (!empty($game->division)) {
                        if ($this->user->admin && !empty($game->assignor)) {
                            $num_assigned++;
                        } elseif ($this->user->name == $game->assignor) {
                            $num_area++;
                            $this->assigned_list[$game->division]++;
                        }
                        $used_list[$game->division] = 1;
                    }
                }
                $num_unassigned = count($this->games) - $num_assigned;

                $uname = $this->user->name;

                if (!$this->user->admin) {

                    if ($locked) {
                        $html .= "Assignments are presently <span style=\"color:$this->colorAlert\">locked</span><br><br>\n";
                    } else {
                        $html .= "Assignments are presently <span style=\"color:$this->colorSuccess\">unlocked</span><br><br>\n";
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

                    if (count($this->limit_list) == 0) {
                        $html .= "There is <span style=\"color:$this->colorWarning\">no</span> match limit at this time<br>\n";
                    } else {
                        if (array_key_exists('all', $this->limit_list)) {
                            $tmp_limit = $this->limit_list['all'];
                            if ($tmp_limit != 'none') {
                                $html .= "There is a <span style=\"color:$this->colorWarning\">$tmp_limit</span> match limit in all divisions<br>\n";
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

                    if (count($this->limit_list) == 0) {
                        $html .= "There is <span style=\"color:$this->colorWarning\">no</span> match limit at this time<br>\n";
                    } else {
                        if (array_key_exists('all', $this->limit_list)) {
                            $tmp_limit = $this->limit_list['all'];
                            if (in_array($tmp_limit, ['999', 'none'])) {
                                $html .= "There is <span style=\"color:$this->colorWarning\">no</span> limit of Area assigned matches at this time<br>\n";
                            }
                        } elseif (!$locked) {
                            foreach ($this->limit_list as $k => $v) {
                                $tmp_assigned = $this->assigned_list[$k];
                                if (isset($used_list[$k]) && $used_list[$k]) {
                                    if ($this->limit_list[$k] == 'none') {
                                        $d = str_replace('_', ' ', $k);
                                        $html .= "You are assigned <span style=\"color:$this->colorWarning\">$tmp_assigned</span> $d matches.  There is <span style=\"color:$this->colorWarning\">no</span> match limit for $d.<br>\n";
                                        $all_at_limit = false;
                                    } else {
                                        $html .= "You are assigned <span style=\"color:$this->colorWarning\">$tmp_assigned</span> matches of your <span style=\"color:$this->colorWarning\">$v</span> match limit for $k<br>\n";
                                        $all_at_limit &= $tmp_assigned >= $v;
                                    }
                                }
                            }
                            if (count($this->assigned_list) < count($used_list)) {
                                $html .= "There is <span style=\"color:$this->colorWarning\">no</span> match limit for all other divisions<br>\n";
                            }
                        }
                    }

                    if ($locked && !array_key_exists('none', $this->limit_list)) {
                        if (!$all_at_limit) {
                            $html .= "You may sign " . $this->user->name . " teams up for neutral matches, but you may not remove them</h3>\n";
                        } else {
                            $html .= "Since " . $this->user->name . " is at or above your limit, you will not be able to sign teams up for matches</h3>\n";
                        }
                    }
                }
                $html .= "</h3>";

                $html .= "<hr class=\"center width25\">";
                $html .= "<h3 class=\"center\" style=\"color:$this->colorAlert\">ACTIONS</h3>\n";
                $html .= "<h3 class=\"center\"><a href=" . $this->getBaseURL(
                        'fullPath'
                    ) . ">View full schedule</a></h3>";

                if ($this->user->admin) {
                    if (!$this->event->archived) {
                        $html .= "<h3 class=\"center\"><a href=" . $this->getBaseURL(
                                'editGamePath'
                            ) . ">Edit matches</a></h3>";
                    }
                    $html .= "<h3 class=\"center\"><a href=" . $this->getBaseURL(
                            'schedPath'
                        ) . ">View Match Assignors</a></h3>";
                    $html .= "<h3 class=\"center\"><a href=" . $this->getBaseURL(
                            'masterPath'
                        ) . ">Select Match Assignors</a></h3>";
                    $html .= "<h3 class=\"center\"><a href=" . $this->getBaseURL(
                            'refsPath'
                        ) . ">Edit Referee Assignments</a></h3>";
                } else {
                    $html .= "<h3 class=\"center\">Goto $uname Schedule: <a href=" . $this->getBaseURL(
                            'schedPath'
                        ) . ">All matches</a> - ";
                    $divs = [];
                    foreach ($this->groups as $group) {
                        $group = explode(' ', $group)[0];
                        if (!isset($divs[$group])) {
                            $divs[$group] = $group;
                        }
                    }

                    foreach ($divs as $div) {
                        if (isset($this->assigned_list[$div]) && $this->assigned_list[$div] > 0) {
                            $d = str_replace('_', ' ', $div);
                            $html .= "<a href=\"" . $this->getBaseURL('schedPath') . "?group=$div\">$d</a>" . $delim;
                        }
                    }
                    $html = substr($html, 0, strlen($html) - 3) . "</h3>";
                    $html .= "<h3 class=\"center\"><a href=" . $this->getBaseURL(
                            'refsPath'
                        ) . ">Edit $uname Referee Assignments</a></h3>";

                }
            } else {
                $html .= "<h3 class=\"center\">There are no matches to schedule</h3>";
//                $html .= "<br><p><a class='info' id='Rick Roberts' href='#'>Rick Roberts</a>";
            }

            if ($this->user->admin) {
                $html .= "<h3 class=\"center\"><a href=" . $this->getBaseURL(
                        'adminPath'
                    ) . ">Admin Functions</a></h3>";
            }
            $html .=
                "<h3 class=\"center\"><a href=" . $this->getBaseURL('endPath') . ">Log Off</a></h3>";

        }

        return $html;

    }
}