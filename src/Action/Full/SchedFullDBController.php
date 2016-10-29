<?php
namespace App\Action\Full;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;

class SchedFullDBController extends AbstractController
{
    private $menu;
    private $justOpen;

    public function __construct(Container $container, SchedulerRepository $repository)
    {

        parent::__construct($container);

        $this->sr = $repository;
        $this->justOpen = false;

    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : null;
        if (!$this->authed) {
            return $response->withRedirect($this->logonPath);
        }

        $this->logger->info("Schedule full page action dispatched");

        $this->event = isset($_SESSION['event']) ? $_SESSION['event'] : false;
        $this->user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

        if (is_null($this->event) || is_null($this->user)) {
            return $response->withRedirect($this->logonPath);
        }

        $this->handleRequest($request);

        $content = array(
            'view' => array(
                'rep' => $this->user,
                'content' => $this->renderFull(),
                'topmenu' => $this->menu(),
                'menu' => $this->menu,
                'title' => $this->page_title,
                'dates' => $this->dates,
                'location' => $this->location,
                'description' => "Full Schedule"
            )
        );

        $this->view->render($response, 'sched.html.twig', $content);

        return $response;
    }
    private function handleRequest($request)
    {
        if (count($_GET)) {
            $this->justOpen = array_key_exists('open', $_GET);
        }

        return null;
    }
    private function renderFull()
    {
        $html = null;
        $event = $this->event;

        if (!empty($event)) {
            $projectKey = $event->projectKey;

            $this->page_title = $event->name;
            $this->dates = $event->dates;
            $this->location = $event->location;

            if($this->user == 'Section 1') {
                $games = $this->sr->getGames($projectKey, '%', true);
            } else {
                $games = $this->sr->getGames($projectKey);
            }

            $has4th = $this->sr->numberOfReferees($projectKey) > 3;

            $html .= "<h3 class=\"center\"> Green shading change indicates different start times</h3>\n";

            $html .= "<table class=\"sched_table\" width=\"100%\">\n";
            $html .= "<tr align=\"center\" bgcolor=\"$this->colorTitle\">";
            $html .= "<th>Game No.</th>";
            $html .= "<th>Date</th>";
            $html .= "<th>Time</th>";
            $html .= "<th>Field</th>";
            $html .= "<th>Division</th>";
            $html .= "<th>Pool</th>";
            $html .= "<th>Home</th>";
            $html .= "<th>Away</th>";
            $html .= "<th>Referee Team</th>";
            $html .= "<th>Referee</th>";
            $html .= "<th>AR1</th>";
            $html .= "<th>AR2</th>";
            if ($has4th) {
                $html .= "<th>4th</th>";
            }
            $html .= "</tr>\n";

            $color1 = $this->colorGroup1;
            $color2 = $this->colorGroup2;
            $testtime = null;

            foreach ($games as $game) {
                if (!$this->justOpen || ($this->justOpen && empty($game->cr))) {
                    $date = date('D, d M', strtotime($game->date));
                    $time = date('H:i', strtotime($game->time));

                    if ( !$testtime ) {
                        $testtime = $time;
                    } elseif ( $testtime != $time && !empty($game->assignor)) {
                        $testtime = $time;
                        $tempcolor = $color1;
                        $color1 = $color2;
                        $color2 = $tempcolor;
                    }

                    if ($game->assignor == $this->user) {
                        $html .= "<tr align=\"center\" bgcolor=\"$color1\">";
                    } elseif (!empty($game->assignor)) {

                        if ($this->user == 'Section 1') {
                            if (empty($game->cr)) {
                                $html .= "<tr align=\"center\" bgcolor=\"$color1\">";
                            } else {
                                $html .= "<tr align=\"center\" bgcolor=\"$this->colorSuccess\">";
                            }
                        } else {
                            $html .= "<tr align=\"center\" bgcolor=\"$this->colorNotGroup\">";
                        }
                    } else {
                        $html .= "<tr align=\"center\" bgcolor=\"$this->colorOpen\">";
                    }

                    $html .= "<td>$game->game_number</td>";
                    $html .= "<td>$date</td>";
                    $html .= "<td>$time</td>";
                    $html .= "<td>$game->field</td>";
                    $html .= "<td>$game->division</td>";
                    $html .= "<td>$game->pool</td>";
                    $html .= "<td>$game->home</td>";
                    $html .= "<td>$game->away</td>";
                    $html .= "<td>$game->assignor</td>";
                    $html .= "<td>$game->cr</td>";
                    $html .= "<td>$game->ar1</td>";
                    $html .= "<td>$game->ar2</td>";
                    if ($has4th) {
                        $html .= "<td>$game->r4th</td>";
                    }
                    $html .= "</tr>\n";
                }
            }

            $html .= "      </table>\n";

            $this->menu = $this->menu();
        }

        return $html;

    }

    private function menu()
    {
        $html = "<h3 align=\"center\" style=\"margin-top: 20px; line-height: 3em;\"><a href=\"$this->greetPath\">Home</a>&nbsp;-&nbsp;\n";
        if ($this->justOpen) {
            $html .= "<a href=\"$this->fullPath\">View full schedule</a>&nbsp;-&nbsp;\n";
        } else {
            $html .= "<a href=\"$this->fullPath?open\">View schedule with no referees</a>&nbsp;-&nbsp;\n";
        }
        if ($this->user == 'Section 1') {
            $html .= "<a href=\"$this->schedPath\">View Assignors</a>&nbsp;-&nbsp;\n";
            $html .= "<a href=\"$this->masterPath\">Schedule referee teams</a>&nbsp;-&nbsp;\n";
            $html .= "<a href=\"$this->refsPath\">Edit referee assignments</a>&nbsp;-&nbsp;\n";
        } else {
            $html .= "<a href=\"$this->schedPath\">Go to $this->user schedule</a>&nbsp;-&nbsp;\n";
            $html .= "<a href=\"$this->refsPath\">Edit $this->user referees</a>&nbsp;-&nbsp;\n";
        }

        $html .= "<a href=\"$this->endPath\">Log off</a>";

        $html .= "<a href=\"$this->fullXlsPath\" class=\"btn btn-primary btn-xs right\" style=\"margin-right: 0\">Export to Excel<i class=\"icon-white icon-circle-arrow-down\"></i></a>\n";
        $html .= "<div class='clear-fix'></div>";

        $html .= "</h3>\n";

        return $html;
    }
}


