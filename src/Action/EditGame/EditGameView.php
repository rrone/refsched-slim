<?php
namespace App\Action\EditGame;

use App\Action\AbstractView;
use App\Action\SchedulerRepository;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class EditGameView extends AbstractView
{
    public function __construct(Container $container, SchedulerRepository $repository)
    {
        parent::__construct($container, $repository);

        $this->sr = $repository;
    }

    public function handler(Request $request, Response $response)
    {
        $this->user = $request->getAttribute('user');
        $this->event = $request->getAttribute('event');

        if ($request->isPost()) {
            $_POST = $request->getParsedBody();
            if (in_array('Update Games', array_values($_POST))) {
                $formData = [];

                foreach ($_POST as $key => $value) {
                    $datKey = explode('+', $key);
                    if (isset($datKey[1])) {
                        $formData['data'][$datKey[0]][$datKey[1]] = $value;
                    }
                }

                if (empty($hdr)) {
                    $formData['hdr'] = array_keys(current(current($formData)));
                }

                $this->sr->modifyGames($formData);
            }
        }
    }

    public function render(Response &$response)
    {
        $content = array(
            'view' => array(
                'admin' => $this->user->admin,
                'content' => $this->renderEditGame(),
                'topmenu' => $this->menu(),
                'menu' => $this->menu(),
                'title' => $this->page_title,
                'dates' => $this->dates,
                'location' => $this->location,
                'description' => "Update Games",
            )
        );

        $this->view->render($response, 'editgame.html.twig', $content);

        return $response;
    }

    protected function renderEditGame()
    {
        $html = null;

        if (!empty($this->event)) {
            if(!empty($this->event->infoLink)){
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

            $games = $this->sr->getGames($projectKey, '%', true);

            if (count($games)) {
                $html .= "<form name=\"editref\" method=\"post\" action=" . $this->getBaseURL('editGamePath') . ">\n";

                $html .= "<input class=\"btn btn-primary btn-xs right\" type=\"submit\" name=\"action\" value=\"Update Games\">\n";
                $html .= "<div class='clear-fix'></div>";

                $html .= "<table class=\"edit-table sched-table\" width=\"100%\">\n";
                $html .= "<tr class=\"center\" bgcolor=\"$this->colorTitle\">";
                $html .= "<th>Game#</th>";
                $html .= "<th>Date</th>";
                $html .= "<th>Time</th>";
                $html .= "<th>Field</th>";
                $html .= "<th>Division</th>";
                $html .= "<th>Pool</th>";
                $html .= "<th>Home</th>";
                $html .= "<th>Away</th>";
                $html .= "</tr>\n";

                foreach ($games as $game) {
                    $time = date('H:i', strtotime($game->time));

                    $html .= "<tr class=\"center\" bgcolor=\"#00FF88\">";
                    $html .= "<td>$game->game_number
                        <input type=\"hidden\" name=\"$game->id+projectKey\" value=\"$projectKey\">
                        <input type=\"hidden\" name=\"$game->id+id\" value=\"$game->id\">
                        <input type=\"hidden\" name=\"$game->id+game_number\" value=\"$game->game_number\">
                        </td>";
                    $html .= "<td><input type=\"text\" name=\"$game->id+date\" value=\"$game->date\" required pattern=\"\d{4})-\d{1,2}-\d{1,2}\" placeholder=\"yyyy-mm-dd\" title=\"Date are in the form yyyy-mm-dd\"></td>";
                    $html .= "<td><input type=\"text\" name=\"$game->id+time\" value=\"$time\" pattern=\"\d{2}:\d{2}\" placeholder=\"hh:mm\" title=\"Time in the form hh:mm\"></td>";
                    $html .= "<td><input type=\"text\" name=\"$game->id+field\" value=\"$game->field\"></td>";
                    $html .= "<td><input type=\"text\" name=\"$game->id+division\" value=\"$game->division\" pattern=\"([U]{1}[0-9]{2}[BG]{1})\" title=\"Divisions are in the form U14G\"></td>";
                    $html .= "<td><input type=\"text\" name=\"$game->id+pool\" value=\"$game->pool\" pattern=\"(\d{1,2}|SF|FIN|CON)\" title=\"Pools are 1-99, 'SF', 'FIN' or 'CON'\"></td>";
                    $html .= "<td><input type=\"text\" name=\"$game->id+home\" value=\"$game->home\"></td>";
                    $html .= "<td><input type=\"text\" name=\"$game->id+away\" value=\"$game->away\"></td>";
                    $html .= "</tr>\n";
                }
                $html .= "</table>\n";
                $html .= "<input class=\"btn btn-primary btn-xs right\" type=\"submit\" name=\"UpdateGames\" value=\"Update Games\">\n";
                $html .= "<div class='clear-fix'></div>";
                $html .= "</form>\n";
            }
        }

        return $html;

    }

    private function menu()
    {
        $html = "<h3 class=\"center\">";

        $html .= "<a href=" . $this->getBaseURL('greetPath') . ">Home</a>&nbsp;-&nbsp;";
        $html .= "<a href=" . $this->getBaseURL('fullPath') . ">View the full game schedule</a>&nbsp;-&nbsp";
        $html .= "<a href=" . $this->getBaseURL('schedPath') . ">View Assignors</a>&nbsp;-&nbsp;";
        $html .= "<a href=" . $this->getBaseURL('masterPath') . ">Select Assignors</a>&nbsp;-&nbsp;";
        $html .= "<a href=" . $this->getBaseURL('refsPath') . ">Edit referee assignments</a>&nbsp;-&nbsp";
        $html .= "<a href=" . $this->getBaseURL('endPath') . ">Log off</a>";

        $html .= "</h3>\n";

        return $html;
    }

}