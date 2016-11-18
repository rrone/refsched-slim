<?php
namespace App\Action\EditRef;

use Slim\Container;
use App\Action\SchedulerRepository;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractView;

class SchedEditRefView extends AbstractView
{
    private $game_id;

    public function __construct(Container $container, SchedulerRepository $schedulerRepository)
    {
        parent::__construct($container, $schedulerRepository);

        $this->sr = $schedulerRepository;
    }
    public function handler(Request $request, Response $response)
    {
        $this->user = $request->getAttribute('user');
        $this->event = $request->getAttribute('event');
        $this->game_id = $request->getAttribute('game_id');

        if($request->isPost()) {
            $_POST = $request->getParsedBody();

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
            }

            unset($_SESSION['game_id']);
        }
    }
    public function render(Response &$response)
    {
        $content = array(
            'view' => array(
                'admin' => $this->user->admin,
                'content' => $this->renderEditRef(),
                'topmenu' => $this->menu(),
                'menu' => $this->menu(),
                'title' => $this->page_title,
                'dates' => $this->dates,
                'location' => $this->location,
                'description' => "Assign " . $this->user->name. " Referees",
            )
        );

        $this->view->render($response, 'sched.html.twig', $content);
    }
    private function renderEditRef()
    {
        $html = null;

        if (!empty($this->event)) {
            $this->page_title = $this->event->name;
            $this->dates = $this->event->dates;
            $this->location = $this->event->location;
            $projectKey = $this->event->projectKey;

            $target_game = $this->sr->gameIdToGameNumber($this->game_id);
            if (!is_null($target_game)) {
                $html .= "<h2 class=\"center\">Enter Referee's First and Last name.<br>" .
                    "<span style=\"color:#FF0000\"><i>NOTE: Adding \"??\" or \"Area name\" is NOT helpful.</i></span></h2><br>";

                if($this->user->admin) {
                    $games = $this->sr->getGames($projectKey, '%', true);
                } else {
                    $games = $this->sr->getGames($projectKey);
                }

                $numRefs = $this->sr->numberOfReferees($projectKey);

                if (count($games)) {
                    foreach ($games as $game) {
                        $date = date('D, d M', strtotime($game->date));
                        $time = date('H:i', strtotime($game->time));
                        if ($game->game_number == $target_game && ($game->assignor == $this->user->name|| $this->user->admin)) {
                            $html .= "<form name=\"editref\" method=\"post\" action=" . $this->getBaseURL('editrefPath') . ">\n";
                            $html .= "<table class=\"sched_table\" width=\"100%\">\n";
                            $html .= "<tr align=\"center\" bgcolor=\"$this->colorTitle\">";
                            $html .= "<th>Game No.</th>";
                            $html .= "<th>Date</th>";
                            $html .= "<th>Time</th>";
                            $html .= "<th>Field</th>";
                            $html .= "<th>Division</th>";
                            $html .= "<th>pool</th>";
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
                            $html .= "<td>$game->pool</td>";
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
            $html .= "You should go to the <a href=" . $this->getBaseURL('refsPath') . ">Referee Edit Page</a></h2>";
        }

        return $html;

    }

    private function menu()
    {
        $html = "<h3 align=\"center\"><a href=" . $this->getBaseURL('greetPath') . ">Home</a>&nbsp;-&nbsp;\n";
        $html .= "<a href=" . $this->getBaseURL('fullPath') . ">View the full game schedule</a>&nbsp;-&nbsp\n";
        if ($this->user->admin) {
            $html .= "<a href=" . $this->getBaseURL('masterPath') . ">Go to " . $this->user->name . " schedule</a>&nbsp;-&nbsp;\n";
        } else {
            $html .= "<a href=" . $this->getBaseURL('refsPath') . ">Go to " . $this->user->name . " schedule</a>&nbsp;-&nbsp;\n";
        }

        $html .= "<a href=" . $this->getBaseURL('endPath') . ">Log off</a></h3>";

        return $html;
    }
}