<?php
namespace App\Action\Refs;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;

class SchedRefsDBController extends AbstractController
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

        $this->logger->info("Schedule refs page action dispatched");

        $this->event = isset($_SESSION['event']) ? $_SESSION['event'] : null;
        $this->user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

        if (is_null($this->event) || is_null($this->user)) {
            return $response->withRedirect($this->logonPath);
        }

        if ($this->handleRequest($request)) {
            $_SESSION['target_id'] = array_keys($_POST);

            return $response->withRedirect($this->editrefPath);
        }

        $content = array(
            'view' => array(
                'rep' => $this->user,
                'content' => $this->renderRefs(),
                'topmenu' => $this->menu(),
                'menu' => $this->menu(),
                'title' => $this->page_title,
                'dates' => $this->dates,
                'location' => $this->location,
                'description' => $this->user . ' Referee Assignments',
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

            if ($this->user == 'Section 1') {
                $games = $this->sr->getGames($projectKey, '%', true);
            } else {
                $games = $this->sr->getGames($projectKey);
            }

            $numRefs = $this->sr->numberOfReferees($projectKey);

            if (count($games)) {
                if ($this->user != 'Section 1') {
                    $html .= "<h2  class=\"center\">You are currently scheduled for the following games</h2></div>\n";
                }
                $html .= "<h3 class=\"center\"> Shading change indicates different start times</h3>\n";
                $html .= "<form name=\"addref\" method=\"post\" action=\"$this->refsPath\">\n";
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
                if ($numRefs > 3) {
                    $html .= "<th>4th</th>";
                }
                $html .= "<th>Edit</th>";
                $html .= "</tr>\n";

                $color1 = $this->colorGroup1;
                $color2 = $this->colorGroup2;
                $testtime = null;

                foreach ($games as $game) {
                    $date = date('D, d M', strtotime($game->date));
                    $time = date('H:i', strtotime($game->time));
                    if ($game->assignor == $this->user || $this->user == 'Section 1') {
                        if (!$game->assignor && $this->user == 'Section 1') {
                            $html .= "<tr align=\"center\" bgcolor=\"$this->colorOpen\">";
                        } else {
                            if ( !$testtime ) { $testtime = $time; }
                            elseif ( $testtime != $time ) {
                                $testtime = $time;
                                $tempcolor = $color1;
                                $color1 = $color2;
                                $color2 = $tempcolor;
                            }
                            $html .= "<tr align=\"center\" bgcolor=\"$color1\">";
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
                        if ($numRefs > 3) {
                            $html .= "<td>$game->r4th</td>";
                        }
                        if ($game->assignor || $this->user == 'Section 1') {
                            $html .= "<td><input class=\"btn btn-primary btn-xs \" type=\"submit\" name=\"$game->id\" value=\"Edit Assignments\"></td>";
                        } else {
                            $html .= "<td>&nbsp;</td>\n";
                        }
                        $html .= "</tr>\n";
                    }
                }
                $html .= "</table>\n";
                $html .= "</form>\n";
            } else {
                $html .= "<h2 class=\"center\">You do not currently have any games scheduled.</h2>\n";
                $html .= "  You should go to the <a href=\"$this->schedPath\">Schedule Page</a></h2>";
            }
        } else {
            $html .= $this->errorCheck();
        }

        return $html;

    }

    private function menu()
    {
        $html = "<h3 align=\"center\"><a href=\"$this->greetPath\">Home</a>&nbsp;-&nbsp;\n";

        $html .= "<a href=\"$this->fullPath\">View the full schedule</a>&nbsp;-&nbsp\n";

        if ($this->user == 'Section 1') {
            $html .= "<a href=\"$this->masterPath\">Schedule referee teams</a>&nbsp;-&nbsp;\n";
        } else {
            $html .= "<a href=\"$this->schedPath\">Go to $this->user schedule</a>&nbsp;-&nbsp;\n";
        }

        $html .= "<a href=\"$this->endPath\">Log off</a></h3>\n";

        return $html;
    }
}


