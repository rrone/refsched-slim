<?php
namespace App\Action\EditRef;


use Slim\Container;
use App\Action\SchedulerRepository;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractView;
use TheIconic\NameParser\Parser;

class SchedEditRefView extends AbstractView
{
    private $game_id;
    private $isPost;

    private $parser;

    /**
     * SchedEditRefView constructor.
     * @param Container $container
     * @param SchedulerRepository $schedulerRepository
     * @param Parser $parser
     */
    public function __construct(Container $container, SchedulerRepository $schedulerRepository, Parser $parser)
    {
        parent::__construct($container, $schedulerRepository);

        $this->sr = $schedulerRepository;
        $this->parser = $parser;
    }

    /**
     * @param Request $request
     * @param Response $response
     */
    public function handler(Request $request, Response $response)
    {
        $this->user = $request->getAttribute('user');
        $this->event = $request->getAttribute('event');
        $this->game_id = $request->getAttribute('game_id');

        if ($request->isPost()) {
            $this->isPost = true;
            $data = $request->getParsedBody();

            if (in_array("Update Assignments", array_values($data))) {

                foreach ($data as $key => &$value) {
                    if(is_string($key)) {
                        $value = $this->user->admin ? $value : $this->stdName($value);
                    }
                }

                $gameNum = $this->sr->gameIdToGameNumber($this->game_id);
                $game = $this->sr->getGameByKeyAndNumber($this->event->projectKey, $gameNum);

                if (!is_null($game) && (($game->assignor == $this->user->name) || $this->user->admin))
                    $this->sr->updateAssignments($data);
            }
            if (in_array("Clear All", array_values($data))) {

                foreach ($data as $key => &$value) {
                    if(is_string($key)) {
                        $value = null;
                    }
                }

                $gameNum = $this->sr->gameIdToGameNumber($this->game_id);
                $game = $this->sr->getGameByKeyAndNumber($this->event->projectKey, $gameNum);

                if (!is_null($game) && (($game->assignor == $this->user->name) || $this->user->admin))
                    $this->sr->updateAssignments($data);

                $this->isPost = false;
            }
        }
    }

    /**
     * @param Response $response
     *
     */
    public function render(Response $response)
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

    /**
     * @return mixed
     */
    public function isPost()
    {
        return $this->isPost;
    }

    /**
     * @return string|null
     *
     */
    private function renderEditRef()
    {
        $html = null;

        if (!empty($this->event)) {
            $projectKey = $this->event->projectKey;
            //refresh event data
            $this->event = $this->sr->getEvent($projectKey);

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
            $show_medal_round = $this->sr->getMedalRound($projectKey);
            $show_medal_round_divisions = $this->sr->getMedalRoundDivisions($projectKey);

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
                $nameRegex = "^(?!Area|area)([A-z\\-\\']{2,20}[,\\s]{1,2}[A-z\\-]{1,20}[,\\s]{0,1}[A-z\\-\\']{1,20}[,\\s]{0,1})$";
                $nameHint = "First Last or Last, First (No initials)";

                if (count($games)) {
                    foreach ($games as $game) {
                        $date = date('D, d M', strtotime($game->date));
                        $time = date('H:i', strtotime($game->time));
                        if ($game->game_number == $target_game && ($game->assignor == $this->user->name || $this->user->admin)) {
                            $html .= "<form name=\"editref\" method=\"post\" action=" . $this->getBaseURL('editrefPath') . ">\n";
                            $html .= "<table class=\"sched-table width100\">\n";
                            $html .= "<tr class=\"center colorTitle\">";
                            $html .= "<th>Match #</th>";
                            $html .= "<th>Date</th>";
                            $html .= "<th>Time</th>";
                            $html .= "<th>Field</th>";
                            $html .= "<th>Division</th>";
                            $html .= "<th>Pool</th>";
                            $html .= "<th>Referee Team</th>";
                            $html .= "<th>Referee</th>";
                            $html .= "<th>AR1</th>";
                            $html .= "<th>AR2</th>";
                            if ($numRefs > 3) {
                                $html .= "<th>4th</th>";
                            }
                            $html .= "</tr>\n";
                            $html .= "<tr class=\"center colorGreen\">";
                            if ($show_medal_round_divisions || !$game->medalRound || $this->user->admin) {
                                $html .= "<td>$game->game_number</td>";
                            } else {
                                $html .= "<td></td>";
                            }
                            $html .= "<td>$date</td>";
                            $html .= "<td>$time</td>";
                            if ($show_medal_round_divisions || !$game->medalRound || $this->user->admin) {
                                $html .= "<td>$game->field</td>";
                                $html .= "<td>$game->division</td>";
                                $html .= "<td>$game->pool</td>";
                            } else {
                                $html .= "<td></td>";
                                $html .= "<td></td>";
                                $html .= "<td></td>";
                            }
                            $html .= "<td>$game->assignor</td>";
                            $html .= "<td><input type=\"text\" name=\"cr\" value=\"$game->cr\" placeholder=\"First Last\" pattern=\"$nameRegex\" title=\"$nameHint\"></td>";
                            $html .= "<td><input type=\"text\" name=\"ar1\" value=\"$game->ar1\" placeholder=\"First Last\" pattern=\"$nameRegex\" title=\"$nameHint\"></td>";
                            $html .= "<td><input type=\"text\" name=\"ar2\" value=\"$game->ar2\" placeholder=\"First Last\" pattern=\"$nameRegex\" title=\"$nameHint\"></td>";
                            if ($numRefs > 3) {
                                $html .= "<td><input type=\"text\" name=\"r4th\" size=\"15\" value=\"$game->r4th\" placeholder=\"First Last\" pattern=\"$nameRegex\"  title=\"$nameHint\"></td>";
                            }
                            $html .= "</tr>\n";
                            $html .= "</table>\n";
                            $html .= "<input class=\"btn btn-primary btn-xs right\" type=\"submit\" name=\"$game->id\" value=\"Update Assignments\">\n";
                            $html .= "<input class=\"btn btn-primary btn-xs right\" type=\"reset\" name=\"reset\" value=\"Reset\">\n";
                            $html .= "<input class=\"btn btn-primary btn-xs right\" type=\"submit\" name=\"$game->id\" value=\"Clear All\">\n";
                            $html .= "<input class=\"btn btn-primary btn-xs right\" type=\"submit\" name=\"cancel\" value=\"Cancel\">\n";
                            $html .= "<div class='clear-fix'></div>";
                            $html .= "</form>\n";
                        }
                    }
                }
            } else {
                $html .= "<h3 class=\"center\"><span style=\"color:$this->colorAlert\">The matching match was not found or your Area was not assigned to it.<br>You might want to check the schedule and try again.</span></h3>\n";
            }
        }

        return $html;
    }

    /**
     * @return string
     *
     */
    private function menu()
    {
        $html = "<h3 class=\"center h3-btn\">";

        $html .= "<a href=" . $this->getBaseURL('greetPath') . ">Home</a>&nbsp;-&nbsp;\n";
        $html .= "<a href=" . $this->getBaseURL('fullPath') . ">View the full schedule</a>&nbsp;-&nbsp\n";
        if ($this->user->admin) {
            if(!$this->event->archived) {
                $html .= "<a href=".$this->getBaseURL('editGamePath').">Edit matches</a>&nbsp;-&nbsp;\n";
            }
            $html .= "<a href=" . $this->getBaseURL('masterPath') . ">Go to " . $this->user->name . " schedule</a>&nbsp;-&nbsp;\n";
        } else {
            $html .= "<a href=" . $this->getBaseURL('refsPath') . ">Go to " . $this->user->name . " schedule</a>&nbsp;-&nbsp;\n";
        }

        $html .= "<a href=" . $this->getBaseURL('endPath') . ">Log off</a>";

        $html .= "</h3>";

        return $html;
    }

    /**
     * @param $name
     * @return string
     */
    private function stdName($name)
    {
        $nameIn = '';

        //deal with Last, First
        if (strpos($name, ',')) {
            $tempName = explode(',', $name);
            foreach ($tempName as $k => $item) {
                if ($k > 0) {
                    $nameIn .= $item . ' ';
                }
            }
            $nameIn .= $tempName[0];
        } else {
            $nameIn = $name;
        }

        //propercase
        $nameOut = (object) $this->parser->parse($nameIn);
        $nameOut = $nameOut->getFirstname() . ' ' . $nameOut->getLastname() . ' '. $nameOut->getSuffix();

        return trim($nameOut);
    }

}