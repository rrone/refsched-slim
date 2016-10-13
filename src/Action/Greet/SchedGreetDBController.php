<?php
namespace App\Action\Greet;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;

class SchedGreetDBController extends AbstractController
{
    public function __construct(Container $container, SchedulerRepository $repository)
    {

        parent::__construct($container);

        $this->sr = $repository;

    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : null;
        if (!$this->authed) {
            return $response->withRedirect($this->logonPath);
        }
        $this->logger->info("Schedule greet page action dispatched");

        $this->event = isset($_SESSION['event']) ? $_SESSION['event'] : null;
        $this->rep = isset($_SESSION['unit']) ? $_SESSION['unit'] : null;

        if (is_null($this->event) || is_null($this->rep)) {
            return $response->withRedirect($this->logonPath);
        }

        $this->handleRequest($request);

        $content = array(
            'view' => array(
                'rep' => $this->rep,
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

            $limits = $this->sr->getLimits($projectKey);
            $groups = $this->sr->getGroups($projectKey);
            foreach ($limits as $group) {
                if ( ($group->division != 'none') && !empty($group->division) ){
                    $limit_list[$group->division] = $group->limit;
                    $used_list[$group->division] = 0;
                    $assigned_list[$group->division] = 0;
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
                if ($this->rep == "Section 1" && !empty($game->assignor)) {
                    $num_assigned++;
                } elseif ($this->rep == $game->assignor) {
                    $num_area++;
                    $assigned_list[$this->divisionAge($game->division)]++;
                }
                $used_list[$this->divisionAge($game->division)] = 1;
            }
            $num_unassigned = count($games) - $num_assigned;

            $html = null;
            $html .= "<h3 class=\"center\">Welcome $this->rep Assignor</h3>\n";
            $html .= "<h3 class=\"center\" style=\"color:$this->colorAlert\">CURRENT STATUS</h3>\n<h3 align=\"center\">";

            if ($this->rep == 'Section 1') {
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
                    $html .= "$this->rep is not currently assigned to any games.<br>";
                } elseif ($num_area == 1) {
                    $html .= "$this->rep is currently assigned to <span style=\"color:$this->colorSuccess\">$num_area</span> game.<br>";
                } else {
                    $html .= "$this->rep is currently assigned to <span style=\"color:$this->colorSuccess\">$num_area</span> games.<br>";
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
                        $html .= "You may sign $this->rep teams up for games but you may not remove them</h3>\n";
                    } else {
                        $html .= "Since $this->rep is at or above your limit, you will not be able to sign teams up for games</h3>\n";
                    }
                }
            }

            $html .= "<hr class=\"center\" width=\"25%\">";
            $html .= "<h3 class=\"center\" style=\"color:$this->colorAlert\">ACTIONS</h3>\n";
            $html .= "<h3 class=\"center\"><a href=\"$this->fullPath\">View the full game schedule</a></h3>";

            if ($this->rep == 'Section 1') {
                $html .= "<h3 class=\"center\"><a href=\"$this->masterPath\">Select Referee Teams</a></h3>";
            } else {
                $html .= "<h3 class=\"center\"><a href=\"$this->schedPath\">Schedule $this->rep Referee Teams</a></h3>";
                $html .= "<h3 class=\"center\">Schedule a division: ";
                foreach ($groups as $group) {
                    $html .= "<a href=\"$this->schedPath?group=$group\">$group</a>" . $delim;
                }
                $html = substr($html, 0, strlen($html) - 3) . "</h3>";
            }

            $html .= "<h3 class=\"center\"><a href=\"$this->refsPath\">Edit Referee Assignments</a></h3>";
            //         $html .= "<h3 class=\"center\"><a href=\"/summary.htm\">Summary of the playoffs</a></h3>";
            $html .= "<h3 class=\"center\"><a href=\"$this->endPath\">LOG OFF</a></h3>";
            $html .= "</center>";
        }

        return $html;

    }
}


