<?php
namespace App\Action\EditRef;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;

class SchedEditRefDBController extends AbstractController
{
    private $target_id;

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

        $this->logger->info("Schedule edit refs page action dispatched");

        $this->event = isset($_SESSION['event']) ? $_SESSION['event'] : false;
        $this->user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
        $this->target_id = isset($_SESSION['target_id']) ? $_SESSION['target_id'] : null;

        if (is_null($this->event) || is_null($this->user)) {
            return $response->withRedirect($this->logonPath);
        }

        if ($request->isPost()) {
            if ($this->handleRequest($request)) {

                return $response->withRedirect($this->refsPath);
            }
        }

        $content = array(
            'view' => array(
                'rep' => $this->user,
                'content' => $this->renderEditRef(),
                'topmenu' => $this->menu(),
                'menu' => $this->menu(),
                'title' => $this->page_title,
                'dates' => $this->dates,
                'location' => $this->location,
                'description' => "Assign " . $this->user . " Referees",
            )
        );

        $this->view->render($response, 'sched.html.twig', $content);

        return $response;

    }

    private function handleRequest($request)
    {

        switch (count($_POST) > 3) {
            case 3:
                $data = $request->getParsedBody();

                foreach ($data as $key => &$value) {
                    if ($value != 'Update Assignments') {
                        $pattern = "/^[a-z ,.'-]+$/i";
                        $matches = [];
                        preg_match($pattern, $value, $matches);
                        if (empty($matches)) {
                            $value = '';
                        }
                    }
                }
                $this->sr->updateAssignments($data);

                return true;
            default:

                return null;
        }

    }

    private function renderEditRef()
    {
        $html = null;

        $event = $this->event;

        if (!empty($event)) {
            $this->page_title = $event->name;
            $this->dates = $event->dates;
            $this->location = $event->location;
            $projectKey = $event->projectKey;

            $target_game = $this->sr->gameIdToGameNumber($this->target_id);
            if (!is_null($target_game)) {
                $html .= "<h2 class=\"center\">Enter Referee's First and Last name.<br>" .
                    "<span style=\"color:#FF0000\"><i>NOTE: Adding \"??\" or \"Area name\" is NOT helpful.</i></span></h2><br>";

                $games = $this->sr->getGames($projectKey);
                $numRefs = $this->sr->numberOfReferees($projectKey);

                if (count($games)) {
                    foreach ($games as $game) {
                        $date = date('D, d M', strtotime($game->date));
                        $time = date('H:i', strtotime($game->time));
                        if ($game->game_number == $target_game && ($game->assignor == $this->user || $this->user == 'Section 1')) {
                            $html .= "<form name=\"editref\" method=\"post\" action=\"$this->editrefPath\">\n";
                            $html .= "<table class=\"sched_table\" width=\"100%\">\n";
                            $html .= "<tr align=\"center\" bgcolor=\"$this->colorTitle\">";
                            $html .= "<th>Game No.</th>";
                            $html .= "<th>Date</th>";
                            $html .= "<th>Time</th>";
                            $html .= "<th>Field</th>";
                            $html .= "<th>Division</th>";
                            $html .= "<th>Referee Team</th>";
                            $html .= "<th>Center</th>";
                            $html .= "<th>AR1</th>";
                            $html .= "<th>AR2</th>";
                            if ($numRefs > 3) {
                                $html .= "<th>4th</th>";
                            }
                            $html .= "</tr>\n";
                            $html .= "<tr align=\"center\" bgcolor=\"#00FF88\">";
                            $html .= "<td>$game->game_number</td>";
                            $html .= "<td>$date</td>";
                            $html .= "<td>$time</td>";
                            $html .= "<td>$game->field</td>";
                            $html .= "<td>$game->division</td>";
                            $html .= "<td>$game->assignor</td>";
                            $html .= "<td><input type=\"text\" name=\"cr\" size=\"15\" value=\"$game->cr\"></td>";
                            $html .= "<td><input type=\"text\" name=\"ar1\" size=\"15\" value=\"$game->ar1\"></td>";
                            $html .= "<td><input type=\"text\" name=\"ar2\" size=\"15\" value=\"$game->ar2\"></td>";
                            if ($numRefs > 3) {
                                $html .= "<td><input type=\"text\" name=\"r4th\" size=\"15\" value=\"$game->r4th\"></td>";
                            }
                            $html .= "</tr>\n";
                            $html .= "</table>\n";
                            $html .= "<input class=\"btn btn-primary btn-xs right\" type=\"submit\" name=\"$game->id\" value=\"Update Assignments\">\n";
                            $html .= "<div class='clear-fix'></div>";
                            $html .= "</form>\n";
                        }
                    }
                }
            } else {
                $html .= "<h3 class=\"center\"><span style=\"color:$this->colorAlert\">The matching game was not found or your Area was not assigned to it.<br>You might want to check the schedule and try again.</span></h3>\n";
            }
        } else {
            $html .= "<h2 class=\"center\">You seem to have gotten here by a different path<br>\n";
            $html .= "You should go to the <a href=\"$this->refsPath\">Referee Edit Page</a></h2>";
        }

        return $html;

    }

    private function menu()
    {
        $html =
            <<<EOD
            		<h3 align="center"><a href="$this->greetPath">Home</a>&nbsp;-&nbsp;
EOD;

        if ($this->user == 'Section 1') {
            $html .= "<a href=\"$this->masterPath\">Go to Section 1 schedule</a>&nbsp;-&nbsp;\n";
        } else {
            $html .= "<a href=\"$this->refsPath\">Go to $this->user schedule</a>&nbsp;-&nbsp;\n";
        }

        $html .=
            <<<EOD
            		<a href="$this->endPath">Log off</a></h3>
EOD;

        return $html;
    }
}


