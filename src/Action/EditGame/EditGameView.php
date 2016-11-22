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
            $data = $request->getParsedBody();
            var_dump($data);
            die();
            $this->sr->updateGame($data);
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
            $this->page_title = $this->event->name;
            $this->dates = $this->event->dates;
            $this->location = $this->event->location;
            $projectKey = $this->event->projectKey;

            $games = $this->sr->getGames($projectKey, '%', true);

            $numRefs = $this->sr->numberOfReferees($projectKey);

            if (count($games)) {
                $html .= "<form name=\"editref\" method=\"post\" action=" . $this->getBaseURL('editGamePath') . ">\n";

                $html .= "<input class=\"btn btn-primary btn-xs right\" type=\"submit\" name=\"UpdateGames\" value=\"Update Games\">\n";
                $html .= "<div class='clear-fix'></div>";

                $html .= "<table class=\"sched_table edit-table col-xs-12\" width=\"100%\">\n";
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
                $html .= "<th>Center</th>";
                $html .= "<th>AR1</th>";
                $html .= "<th>AR2</th>";
                if ($numRefs > 3) {
                    $html .= "<th>4th</th>";
                }
                $html .= "<th></th>";
                $html .= "</tr>\n";

                foreach ($games as $game) {
                    $html .= "<tr align=\"center\" bgcolor=\"#00FF88\">";
                    $html .= "<td>$game->game_number</td>";
                    $html .= "<td>
                                <div class=\"input-group date form_date col-xs-12\" data-date=\"\" data-date-format=\"yyyy-mm-dd\"
                                    data-link-field=\"dtp_input-$game->id\" data-link-format=\"yyyy-mm-dd\">
                                    <input class=\"form-control col-xs-6\" type=\"text\" name=\"$game->id+date\" value=\"$game->date\"
                                       readonly>
                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                </div>
                                <input type=\"hidden\" id=\"dtp_input-$game->id\" value=\"\"/>
                                </td>";
                    $html .= "<td>
                                <div class=\"input-group date form_time col-xs-12\" data-date=\"\" data-date-format=\"hh:ii\"
                                 data-link-field=\"dtp_input-$game->id\" data-link-format=\"hh:ii\">
                                    <input class=\"form-control col-xs-9\" type=\"text\" name=\"$game->id+time\" value=\"$game->time\"
                                       readonly>
                                    <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-time\"></span></span>
                                </div>
                                <input type=\"hidden\" id=\"dtp_input-$game->id\" value=\"\"/>
                                </td>";
                    $html .= "<td><input type=\"text\" name=\"$game->id+field\" size=\"15\" value=\"$game->field\"></td>";
                    $html .= "<td><input type=\"text\" name=\"$game->id+division\" size=\"15\" value=\"$game->division\"></td>";
                    $html .= "<td><input type=\"text\" name=\"$game->id+pool\" size=\"15\" value=\"$game->pool\"></td>";
                    $html .= "<td><input type=\"text\" name=\"$game->id+home\" size=\"15\" value=\"$game->home\"></td>";
                    $html .= "<td><input type=\"text\" name=\"$game->id+away\" size=\"15\" value=\"$game->away\"></td>";
                    $html .= "<td><input type=\"text\" name=\"$game->id+assignor\" size=\"15\" value=\"$game->assignor\"></td>";
                    $html .= "<td><input type=\"text\" name=\"$game->id+cr\" size=\"15\" value=\"$game->cr\"></td>";
                    $html .= "<td><input type=\"text\" name=\"$game->id+ar1\" size=\"15\" value=\"$game->ar1\"></td>";
                    $html .= "<td><input type=\"text\" name=\"$game->id+ar2\" size=\"15\" value=\"$game->ar2\"></td>";
                    if ($numRefs > 3) {
                        $html .= "<td><input type=\"text\" name=\"$game->id+r4th\" size=\"15\" value=\"$game->r4th\"></td>";
                    }
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
        $html = "<h3 align=\"center\"><a href=" . $this->getBaseURL('greetPath') . ">Home</a>&nbsp;-&nbsp;\n";
        $html .= "<a href=" . $this->getBaseURL('fullPath') . ">View the full game schedule</a>&nbsp;-&nbsp\n";
        $html .= "<a href=" . $this->getBaseURL('masterPath') . ">Go to " . $this->user->name . " schedule</a>&nbsp;-&nbsp;\n";
        $html .= "<a href=" . $this->getBaseURL('endPath') . ">Log off</a></h3>";

        return $html;
    }

}