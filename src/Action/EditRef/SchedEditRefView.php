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

        if ($request->isPost()) {
            $data = $request->getParsedBody();

            if (array_key_exists("Update Assignments", array_keys($data))) {

                foreach ($data as $key => &$value) {
                    $value = $this->stdName($value);
                }

                $gameNum = $this->sr->gameIdToGameNumber($this->game_id);
                $game = $this->sr->getGameByKeyAndNumber($this->event->projectKey, $gameNum);

                if (!is_null($game) && (($game->assignor == $this->user->name) || $this->user->admin))
                    $this->sr->updateAssignments($data);
            }
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
                'description' => "Assign " . $this->user->name . " Referees",
            )
        );

        $this->view->render($response, 'sched.html.twig', $content);
    }

    private function renderEditRef()
    {
        $html = null;

        if (!empty($this->event)) {
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
            $projectKey = $this->event->projectKey;
            $show_medal_round = $this->sr->getMedalRound($projectKey);

            $target_game = $this->sr->gameIdToGameNumber($this->game_id);
            $game = $this->sr->getGameByKeyAndNumber($projectKey, $target_game);

            if (!is_null($target_game) && (($game->assignor == $this->user->name) || $this->user->admin)) {
                $html .= "<h2 class=\"center\">Enter Referee's First and Last name.</h2><br>";

                if ($this->user->admin) {
                    $games = $this->sr->getGames($projectKey, '%', true);
                } else {
                    $games = $this->sr->getGames($projectKey, '%', $show_medal_round);
                }

                $numRefs = $this->sr->numberOfReferees($projectKey);
                $nameRegex = "^('Area')|([A-Z]{1}[a-z]{1,20}[, ]{0,2}[A-Z]{1}[a-z]{1,20}[, ]{0,2}[A-Z]{0,1}[a-z]{0,20}){0,1}";
                $nameHint = "First Last or Last, First (Proper case; no initials)";

                if (count($games)) {
                    foreach ($games as $game) {
                        $date = date('D, d M', strtotime($game->date));
                        $time = date('H:i', strtotime($game->time));
                        if ($game->game_number == $target_game && ($game->assignor == $this->user->name || $this->user->admin)) {
                            $html .= "<form name=\"editref\" method=\"post\" action=" . $this->getBaseURL('editrefPath') . ">\n";
                            $html .= "<table class=\"sched-table\" width=\"100%\">\n";
                            $html .= "<tr class=\"center\" bgcolor=\"$this->colorTitle\">";
                            $html .= "<th>Game#</th>";
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
                            $html .= "<tr class=\"center\" bgcolor=\"#00FF88\">";
                            $html .= "<td>$game->game_number</td>";
                            $html .= "<td>$date</td>";
                            $html .= "<td>$time</td>";
                            $html .= "<td>$game->field</td>";
                            $html .= "<td>$game->division</td>";
                            $html .= "<td>$game->pool</td>";
                            $html .= "<td>$game->assignor</td>";
                            $html .= "<td><input type=\"text\" name=\"cr\" value=\"$game->cr\" placeholder=\"First Last\" pattern=\"$nameRegex\" title=\"$nameHint\"></td>";
                            $html .= "<td><input type=\"text\" name=\"ar1\" value=\"$game->ar1\" placeholder=\"First Last\" pattern=\"$nameRegex\" title=\"$nameHint\"></td>";
                            $html .= "<td><input type=\"text\" name=\"ar2\" value=\"$game->ar2\" placeholder=\"First Last\" pattern=\"$nameRegex\" title=\"$nameHint\"></td>";
                            if ($numRefs > 3) {
                                $html .= "<td><input type=\"text\" name=\"r4th\" size=\"15\" value=\"$game->r4th\" placeholder=\"First Last\" pattern=\"$nameRegex\"  title=\"$nameHint\"></td>";
                            }
                            $html .= "</tr>\n";
                            $html .= "</table>\n";
                            $html .= "<input class=\"btn btn-primary btn-xs right\" type=\"submit\" name=\"cancel\" value=\"Cancel\">\n";
                            $html .= "<input class=\"btn btn-primary btn-xs right\" type=\"submit\" name=\"$game->id\" value=\"Update Assignments\">\n";
                            $html .= "<div class='clear-fix'></div>";
                            $html .= "</form>\n";
                        }
                    }
                }
            } else {
                $html .= "<h3 class=\"center\"><span style=\"color:$this->colorAlert\">The matching game was not found or your Area was not assigned to it.<br>You might want to check the schedule and try again.</span></h3>\n";
            }
        }

        return $html;
    }

    private function menu()
    {
        $html = "<h3 class=\"center\">";

        $html .= "<a href=" . $this->getBaseURL('greetPath') . ">Home</a>&nbsp;-&nbsp;\n";
        $html .= "<a href=" . $this->getBaseURL('fullPath') . ">View the full game schedule</a>&nbsp;-&nbsp\n";
        if ($this->user->admin) {
            $html .= "<a href=" . $this->getBaseURL('editGamePath') . ">Edit games</a>&nbsp;-&nbsp;\n";
            $html .= "<a href=" . $this->getBaseURL('masterPath') . ">Go to " . $this->user->name . " schedule</a>&nbsp;-&nbsp;\n";
        } else {
            $html .= "<a href=" . $this->getBaseURL('refsPath') . ">Go to " . $this->user->name . " schedule</a>&nbsp;-&nbsp;\n";
        }

        $html .= "<a href=" . $this->getBaseURL('endPath') . ">Log off</a>";

        $html .= "</h3>";

        return $html;
    }

    private function stdName($name)
    {
        $nameOut = '';

        //deal with Last, First
        if (strpos($name, ',')) {
            $tempName = explode(',', $name);
            foreach ($tempName as $k => $item) {
                if ($k > 0) {
                    $nameOut .= $item . ' ';
                }
            }
            $nameOut .= $tempName[0];
        } else {
            $nameOut = $name;
        }

        //propercase
        $tempName = explode(' ', strtolower($nameOut));

        $nameOut = '';
        foreach ($tempName as $item) {
            $nameOut .= ucfirst($item) . ' ';
        }

        return trim($nameOut);
    }

}