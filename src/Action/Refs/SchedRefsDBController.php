<?php
namespace App\Action\Refs;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;

class SchedRefsDBController extends AbstractController
{
    private $num_assigned;
    private $bottommenu;

    public function __construct(Container $container, SchedulerRepository $repository)
    {

        parent::__construct($container);

        $this->sr = $repository;

    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : null;
        if (!$this->authed) {
            return $response->withRedirect($this->container->get('logonPath'));
        }

        $this->event = isset($_SESSION['event']) ? $_SESSION['event'] : null;
        $this->user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

        if (is_null($this->event) || is_null($this->user)) {
            return $response->withRedirect($this->container->get('logonPath'));
        }

        $this->logStamp($request);

        if ($this->handleRequest($request)) {
            $_SESSION['target_id'] = array_keys($_POST);

            return $response->withRedirect($this->container->get('editrefPath'));
        }

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

        return $response;

    }

    private function handleRequest($request)
    {
        return $request->isPost();
    }

    private function renderRefs()
    {
        $html = null;

        $event = $this->event;

        if (!empty($event)) {
            $this->page_title = $event->name;
            $this->dates = $event->dates;
            $this->location = $event->location;
            $projectKey = $event->projectKey;

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
                $html .= "<h3 class=\"center\"> Shading change indicates different start times</h3>\n";
                $html .= "<form name=\"addref\" method=\"post\" action=\"$this->container->get('refsPath')\">\n";
                $html .= "<table class=\"sched_table\" width=\"100%\">\n";
                $html .= "<tr align=\"center\" bgcolor=\"$this->colorTitle\">";
                $html .= "<th>Game No.</th>";
                $html .= "<th>Date</th>";
                $html .= "<th>Time</th>";
                $html .= "<th>Field</th>";
                $html .= "<th>Division</th>";
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
                            if ( !$testtime ) {
                                $testtime = $time;
                            }
                            elseif ( $testtime != $time && !empty($game->assignor)) {
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
                            if (empty($game->cr) && empty($game->ar1) && empty($game->ar2) ) {
                                $html .= "<tr align=\"center\" bgcolor=\"$this->colorUnassigned\">";
                                //open AR  or 4th slots
                            }elseif (empty($game->ar1) || empty($game->ar2) || ($has4th && empty($game->r4th))) {
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
                $this->bottommenu = "<h3 class=\"center\">You should go to the <a href=\"$this->container->get('schedPath')\">Schedule Page</a></h3>";
            }
        } else {
            $html .= $this->errorCheck();
        }

        return $html;

    }

    private function menu()
    {
        $html = "<h3 align=\"center\"><a href=\"$this->container->get('greetPath')\">Home</a>&nbsp;-&nbsp;\n";

        $html .= "<a href=\"$this->container->get('fullPath')\">View the full schedule</a>&nbsp;-&nbsp\n";

        if ($this->user->admin) {
            $html .= "<a href=\"$this->container->get('schedPath')\">View Assignors</a>&nbsp;-&nbsp;\n";
            $html .= "<a href=\"$this->container->get('masterPath')\">Select Assignors</a>&nbsp;-&nbsp;\n";
        } else {
            $html .= "<a href=\"$this->container->get('schedPath')\">Go to ". $this->user->name . " schedule</a>&nbsp;-&nbsp;\n";
        }

        $html .= "<a href=\"$this->container->get('endPath')\">Log off</a></h3>\n";

        return $html;
    }
}


