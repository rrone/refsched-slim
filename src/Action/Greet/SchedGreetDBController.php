<?php
namespace App\Action\Greet;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;
use App\Action\SessionManager;

class SchedGreetDBController extends AbstractController
{
    public function __construct(Container $container, SchedulerRepository $repository, SessionManager $sessionManager)
    {
        parent::__construct($container, $sessionManager);

        $this->sr = $repository;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->authed = isset($GLOBALS['authed']) ? $GLOBALS['authed'] : null;

        if (!$this->authed) {
            return $response->withRedirect($this->logonPath);
        }
        $this->logger->info("Schedule greet page action dispatched");

        $this->event = isset($GLOBALS['event']) ? $GLOBALS['event'] : null;
        $this->user = isset($GLOBALS['user']) ? $GLOBALS['user'] : null;

        if (is_null($this->event) || is_null($this->user)) {
            return $response->withRedirect($this->logonPath);
        }

        $this->handleRequest($request);

        $content = array(
            'view' => array(
                'rep' => $this->user,
                'content' => $this->renderGreet(),
                'title' => $this->page_title,
                'dates' => $this->dates,
                'location' => $this->location,
            )
        );

        $this->view->render($response, 'sched.html.twig', $content);

        return $response;

    }

    private function handleRequest($request)
    {
    }

    private function renderGreet()
    {
        $html = null;

        $event = $this->event;

        if (!empty($event)) {
            $projectKey = $event->projectKey;

            $used_list = null;
            $assigned_list = null;
            $limit_list = null;

            $groups = $this->sr->getGroups($projectKey);
            foreach ($groups as $group) {
                $used_list[$group] = 0;
                $assigned_list[$group] = 0;
            }

            $limits = $this->sr->getLimits($projectKey);
            foreach ($limits as $group) {
                if ( ($group->division != 'none') && !empty($group->division) ){
                    $limit_list[$group->division] = $group->limit;
                }
            }

            $this->page_title = $event->name;
            $this->dates = $event->dates;
            $this->location = $event->location;

            $locked = $this->sr->getLocked($projectKey);

            $games = $this->sr->getGames($projectKey);
            $delim = ' - ';
            $num_assigned = 0;
            $num_area = 0;
            $oneatlimit = 0;

            foreach ($games as $game) {
                if ($this->user == "Section 1" && !empty($game->assignor)) {
                    $num_assigned++;
                } elseif ($this->user == $game->assignor) {
                    $num_area++;
                    $assigned_list[$this->divisionAge($game->division)]++;
                }
                $used_list[$this->divisionAge($game->division)] = 1;
            }
            $num_unassigned = count($games) - $num_assigned;

            $html = null;
            $html .= "<h3 class=\"center\">Welcome $this->user Assignor</h3>\n";
            $html .= "<h3 class=\"center\" style=\"color:$this->colorAlert\">CURRENT STATUS</h3>\n<h3 align=\"center\">";

            if ($this->user == 'Section 1') {
                if ($locked) {
                    $html .= "The schedule is:&nbsp;<span style=\"color:$this->colorAlert\">Locked</span>&nbsp;-&nbsp;(<a href=\"$this->unlockPath\">Unlock</a> the schedule now)<br>\n";
                } else {
                    $html .= "The schedule is:&nbsp;<span style=\"color:$this->colorSuccess\">Unlocked</span>&nbsp;-&nbsp;(<a href=\"$this->lockPath\">Lock</a> the schedule now)<br>\n";
                }

                //get the grammar right
                if ($num_assigned == 1 && $num_unassigned == 1) {
                    $html .= "<span style=\"color:#008800\">$num_assigned</span> game is assigned and <span style=\"color:$this->colorAlert\">$num_unassigned</span> is unassigned<br>\n";
                } elseif ($num_assigned > 1 && $num_unassigned == 1) {
                    $html .= "<span style=\"color:#008800\">$num_assigned</span> games are assigned and <span style=\"color:$this->colorAlert\">$num_unassigned</span> is unassigned<br>\n";
                } elseif ($num_assigned == 1 && $num_unassigned > 1) {
                    $html .= "<span style=\"color:#008800\">$num_assigned</span> game is assigned and <span style=\"color:$this->colorAlert\">$num_unassigned</span> are unassigned<br>\n";
                } else {
                    $html .= "<span style=\"color:#008800\">$num_assigned</span> games are assigned and <span style=\"color:$this->colorAlert\">$num_unassigned</span> are unassigned<br>\n";
                }

                if (count($limit_list) == 0){
                    $html .= "There is <span style=\"color:$this->colorWarning\">no</span> game limit at this time</h3>\n";
                } else {
                    if (array_key_exists('all', $limit_list)) {
                        $tmplimit = $limit_list['all'];
                        if ($tmplimit != 'none') {
                            $html .= "There is a <span style=\"color:$this->colorWarning\">$tmplimit</span> game limit in all divisions</h3>\n";
                        } else {
                            $html .= "There is <span style=\"color:$this->colorWarning\">no</span> game limit at this time</h3>\n";
                        }
                    } else {
                        foreach ($limit_list as $k => $v) {
                            if ($used_list[$k]) {
                                if ($v == 'none') {
                                    $html .= "There is <span style=\"color:$this->colorWarning\">no</span> game limit for $k<br>\n";
                                } else {
                                    $html .= "There is a <span style=\"color:$this->colorWarning\">$v</span> game limit for $k<br>\n";
                                }
                            }
                        }
                        if (count($assigned_list) < count($used_list)){
                            $html .= "There is <span style=\"color:$this->colorWarning\">no</span> game limit for all other divisions<br>\n";
                        }
                        $html .= "</h3>\n";
                    }
                }

            } else {
                if ($num_area == 0) {
                    $html .= "$this->user is not currently assigned to any games.<br>";
                } elseif ($num_area == 1) {
                    $html .= "$this->user is currently assigned to <span style=\"color:$this->colorSuccess\">$num_area</span> game.<br>";
                } else {
                    $html .= "$this->user is currently assigned to <span style=\"color:$this->colorSuccess\">$num_area</span> games.<br>";
                }

                if (count($limit_list) == 0){
                    $html .= "There is <span style=\"color:$this->colorWarning\">no</span> game limit at this time</h3>\n";
                } else {
                    if (array_key_exists('all', $limit_list)) {
                        $tmplimit = $limit_list['all'];
                        if ($tmplimit != 'none') {
                            $html .= "There is a limit of <span style=\"color:$this->colorWarning\">$tmplimit</span> Area assigned games in all divisions at this time</h3>\n";
                        } else {
                            $html .= "There is <span style=\"color:$this->colorWarning\">no</span> limit of Area assigned games at this time</h3>\n";
                        }
                    } else {
                        foreach ($limit_list as $k => $v) {
                            $tmpassigned = $assigned_list[$k];
                            if ($used_list[$k]) {
                                $html .= "You have assigned <span style=\"color:$this->colorWarning\">$tmpassigned</span> of your <span style=\"color:$this->colorWarning\">$v</span> game limit for $k<br>\n";
                                if ($tmpassigned >= $v) {
                                    $oneatlimit = true;
                                }
                            }
                        }
                        if (count($assigned_list) < count($used_list)){
                            $html .= "There is <span style=\"color:$this->colorWarning\">no</span> game limit for all other divisions<br>\n";
                        }
                        $html .= "</h3>\n";
                    }
                }
                if ($locked && !array_key_exists('none', $limit_list)) {
                    $html .= "<h3 class=\"center\" style=\"style=\"color:$this->colorAlert\">The schedule is presently locked<br>\n";
                    if (!$oneatlimit) {
                        $html .= "You may sign $this->user teams up for games but you may not remove them</h3>\n";
                    } else {
                        $html .= "Since $this->user is at or above your limit, you will not be able to sign teams up for games</h3>\n";
                    }
                }
            }

            $html .= "<hr class=\"center\" width=\"25%\">";
            $html .= "<h3 class=\"center\" style=\"color:$this->colorAlert\">ACTIONS</h3>\n";
            $html .= "<h3 class=\"center\"><a href=\"$this->fullPath\">View the full game schedule</a></h3>";

            if ($this->user == 'Section 1') {
                $html .= "<h3 class=\"center\"><a href=\"$this->masterPath\">Select Assignors for games</a></h3>";
            } else {
                $html .= "<h3 class=\"center\">Goto $this->user Schedule: <a href=\"$this->schedPath\">All games</a> - ";
                foreach ($groups as $group) {
                    $html .= "<a href=\"$this->schedPath?group=$group\">$group</a>" . $delim;
                }
                $html = substr($html, 0, strlen($html) - 3) . "</h3>";
            }

            $html .= "<h3 class=\"center\"><a href=\"$this->refsPath\">Edit $this->user Referee Assignments</a></h3>";
            //         $html .= "<h3 class=\"center\"><a href=\"/summary.htm\">Summary of the playoffs</a></h3>";
            $html .= "<h3 class=\"center\"><a href=\"$this->endPath\">LOG OFF</a></h3>";
            $html .= "</center>";
        }

        return $html;

    }
}


