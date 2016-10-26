<?php
namespace App\Action\Sched;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;

class SchedSchedDBController extends AbstractController
{
    private $showgroup;
    private $num_assigned;

    public function __construct(Container $container, SchedulerRepository $repository) {
		
		parent::__construct($container);
        
        $this->sr = $repository;

        $this->showgroup = null;
		
    }
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : null;
         if (!$this->authed) {
            return $response->withRedirect($this->logonPath);
         }

        $this->logger->info("Schedule schedule database page action dispatched");

		$this->event = isset($_SESSION['event']) ?  $_SESSION['event'] : false;
        $this->user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

        if (is_null($this->event) || is_null($this->user)) {
            return $response->withRedirect($this->logonPath);
        }

        if ($this->user == 'Section 1') {
            return $response->withRedirect($this->masterPath);
        }

        $this->handleRequest($request);

        $content = array(
            'view' => array (
                'content' => $this->renderSched(),
                'topmenu' => $this->menu(),
                'menu' => null,
                'title' => $this->page_title,
				'dates' => $this->dates,
				'location' => $this->location,
				'description' => $this->user . ' Schedule',
				'message' => $this->msg,
            )
        );        

        $this->view->render($response, 'sched.html.twig', $content);

        return $response;

    }
	private function handleRequest($request)
    {
        if ($request->isPost() && !$this->isRepost($request)) {

            if ($this->user != 'Section 1') {
                $projectKey = $this->event->projectKey;
                $locked = $this->sr->getLocked($projectKey);
                $this->msg = null;
                $limit_list = [];

                //load limits if any or none
                $limits = $this->sr->getLimits($projectKey);
                $no_limit = false;
                if (!count($limits)) {
                    $no_limit = true;
                } else {
                    foreach ($limits as $group) {
                        $limit_list[$group->division] = $group->limit;
                    }
                }

                $array_of_keys = array_keys($_POST);

                //parse the POST data
                $this->showgroup = !empty($_POST[ 'group' ]) ? $_POST[ 'group' ] : null;

                $adds = [];
                $assign = [];
                foreach ($array_of_keys as $key) {
                    $change = explode(':', $key);
                    switch ($change[0]) {
                        case 'assign':
                            $adds[$change[1]] = $this->user;
                            break;
                        case 'games':
                            $assign[$change[1]] = $this->user;
                            break;
                        default:
                            continue;
                    }
                }

                if (!$locked) {
                    //remove drops if not locked
                    $assigned_games = $this->sr->getGamesByRep($projectKey, $this->user);

                    if (count($assign) != count($assigned_games)) {
                        $removed = [];
                        $unassign = [];
                        foreach ($assigned_games as $game) {
                            if (!in_array($game->id, array_keys($assign))){
                                if (is_null($this->showgroup) || ($this->showgroup == $this->divisionAge($game->division))) {
                                    $removed[$game->id] = $game;
                                    $unassign[$game->id] = '';
                                    $data = array(
                                        'cr' => '',
                                        'ar1' => '',
                                        'ar2' => '',
                                        'r4th' => '',
                                        $game->id => 'Update Assignments',
                                    );

                                    $this->sr->updateAssignments($data);
                                    $this->msg .= "<p>You have <strong>removed</strong> your referee team from $game->division Game No. $game->game_number on $game->date at $game->time on $game->field</p>";
                                }
                            }
                        }

                        $this->sr->updateAssignor($unassign);
                    }
                }

                //initialize counting groups
                $assigned_games = $this->sr->getGamesByRep($projectKey, $this->user);
                foreach ($assigned_games as $game) {
                    $div = $this->divisionAge($game->division);
                    if (!isset($games_now[$div])) {
                        $games_now[$div] = 0;
                    }
                    $games_now[$div]++;
                }

                if (count($adds)) {
                    //Update based on add/returned games
                    $added = [];
                    $unavailable = [];
                    $games = $this->sr->getGames($projectKey);
                    foreach ($games as $game) {
                        $date = date('D, d M',strtotime($game->date));
                        $time = date('H:i', strtotime($game->time));
                        $div = $this->divisionAge($game->division);
                        //ensure all indexes exist
                        $games_now[$div] = isset($games_now[$div]) ? $games_now[$div] : 0;
                        $atLimit[$div] = isset($atLimit[$div]) ? $atLimit[$div] : 0;
                        //if requested
                        if (in_array($game->id, array_keys($adds))) {
                            //and available
                            if (empty($game->assignor)) {
                                //and below the limit if there is one
                                if (is_null($limit_list[$div]) || $games_now[$div] < $limit_list[$div] || $no_limit) {
                                    //make the assignment
                                    $data = [$game->id => $this->user];
                                    $this->sr->updateAssignor($data);
                                    $added[$game->id] = $game;
                                    $games_now[$div]++;
                                    $this->msg .= "<p>You have <strong>scheduled</strong> $game->division Game No. $game->game_number on $date on $game->field at $time</p>";
                                } else {
                                    $atLimit[$div]++;
                                    $this->msg .= "<p>You have <strong>not scheduled</strong> $game->division Game No. $game->game_number on $date on $game->field at $time because you are at your game limit!</p>";
                                }
                            } else {
                                $unavailable[$game->id] = $game;
                                $this->msg = "<p>Sorry, $game->division Game No. $game->game_number has been scheduled by $game->assignor</p>";
                            }
                        }
                    }

                }
            }
        }

        return null;
    }
    private function renderSched()
    {
        $html = null;
        
		$event = $this->event;
        
		if (!empty($event)) {
			$projectKey = $event->projectKey;
	
			if ( count( $_GET ) && array_key_exists( 'group', $_GET ) ) {
			   $this->showgroup = $_GET[ 'group' ];
			}

			$color1 = '#D3D3D3';
			$color2 = '#B7B7B7';
			$allatlimit = true;
			$oneatlimit = false;
			$showavailable = false;
			$a_init = substr( $this->user, -1 );
			$assigned_list = null;
            $limit_list = [];
			
			if ( $this->user != 'Section 1') {
                $groups = $this->sr->getGroups($projectKey);
                foreach ($groups as $group) {
                    $assigned_list[$group] = 0;
                    $limit_list[$group] = 'none';
                }

                $limits = $this->sr->getLimits($projectKey);
				foreach($limits as $group){
					$limit_list[ $group->division ] = $group->limit;
				}
				if ( !count( $limit_list ) ) {
					$limit_list[ 'none' ] = 1;
				}
			}
			else {
				$limit_list[ 'none' ] = 1;
			}
			
			$this->page_title = $event->name;
			$this->dates = $event->dates;
			$this->location = $event->location;
	
			$this->num_assigned = 0;		
			$kount = 0;
			$testtime = null;
	
			$locked = $this->sr->getLocked($projectKey);
			$_SESSION['locked'] = $locked;
	
			$games = $this->sr->getGames($projectKey, $this->showgroup);

			foreach( $games as $game ) {
				$game_id[] = $game->id;
				$game_no[] = $game->game_number;
                $date[] = date('D, d M',strtotime($game->date));
				$field[] = $game->field;
				$time[] = date('H:i', strtotime($game->time));
				$div[] = $game->division;
                $pool[] = $game->pool;
				$home[] = $game->home;
				$away[] = $game->away;
				$ref_team[] = $game->assignor;
				if ( $game->assignor == $this->user ) {
					$this->num_assigned++;
					if (isset($assigned_list[ $this->divisionAge( $game->division ) ])) {
						$assigned_list[ $this->divisionAge( $game->division ) ]++;
					}
					else {
                        $assigned_list[ $this->divisionAge( $game->division ) ] = 1;
                    }
			   }
			   $cr[] = $game->cr;
			   $ar1[] = $game->ar1;
			   $ar2[] = $game->ar2;
			   $r4th[] = $game->r4th;
			   
			   $kount = count($games);
			}

			if ( $locked && array_key_exists( 'none', $limit_list ) ) {
				$html .= "<h3 class=\"center\"><span style=\"color:$this->colorAlert\">The schedule has been locked<br>You may sign up for games but not unassign yourself</span></h3>\n";
				$allatlimit = false;
                $showavailable = true;
			}
			elseif ( $locked && array_key_exists( 'all', $limit_list ) && $this->num_assigned < $limit_list[ 'all' ] ) {
				$html .= "<h3 class=\"center\"><span style=\"color:$this->colorAlert\">The schedule has been locked<br>You may sign up for games but not unassign yourself</span></h3>\n";
				$allatlimit = false;
                $showavailable = true;
			}
			elseif ( $locked && array_key_exists( 'all', $limit_list ) && $this->num_assigned == $limit_list[ 'all' ] ) {
				$html .= "<h3 class=\"center\"><span style=\"color:$this->colorAlert\">The schedule has been locked and you are at your game limit<br>\nYou will not be able to unassign yourself from games to sign up for others<br>\nThe submit button on this page has been disabled and available games are not shown<br>\nYou probably want to <a href=\"$this->greetPath\">Go to the Main Page</a> or <a href=\"$this->endPath\">Log Off</a></span></h3>\n";
			}
			elseif ( $locked && array_key_exists( 'all', $limit_list ) && $this->num_assigned > $limit_list[ 'all' ] ) {
				$html .= "<h3 class=\"center\"><span style=\"color:$this->colorAlert\">The schedule has been locked and you are above your game limit<br>\nThe extra games were probably assigned by the Section staff<br>\nYou will not be able to unassign yourself from games to sign up for others<br>\nThe Submit button has been disabled and available games are not shown<br>\nYou probably want to <a href=\"$this->greetPath\">Go to the Main Page</a> or <a href=\"$this->endPath\">Log Off</a></span></h3>\n";
			}
			elseif ( !$locked && array_key_exists( 'all', $limit_list ) && $this->num_assigned < $limit_list['all'] ) {
				$tmplimit = $limit_list['all'];
				$html .= "<h3 class=\"center\">You are currently assigned to <span style=\"color:$this->colorAlert\">$this->num_assigned</span> of your <span style=\"color:$this->colorAlert\">$tmplimit</span> games</h3>\n";
                $showavailable = true;
			}
			elseif ( !$locked && array_key_exists( 'all', $limit_list ) && $this->num_assigned == $limit_list['all'] ) {
			    $html .= "<h3 class=\"center\"><span style=\"color:$this->colorAlert\">You are at your game limit<br>You will have to unassign yourself from games to sign up for others</span></h3>\n";
                $showavailable = true;
			}
			elseif ( !$locked && array_key_exists( 'all', $limit_list ) && $this->num_assigned > $limit_list['all'] ) {
			    $html .= "<h3 class=\"center\"><span style=\"color:$this->colorAlert\">You are above your game limit<br>\nThe extra games were probably assigned by the Section staff<br>\nIf you continue from here you will not be able to keep all the games you are signed up for and may lose some of the games you already have<br>\nIf you want to keep these games and remain over the game limit it is recommended that you do not hit submit but do something else instead<br>\n<a href=\"$this->greetPath\">Go to the Main Page</a></span></h3>\n";
                $showavailable = true;
			}
			elseif ( $locked && count( $limit_list ) ) {
				$html .= "<h3 class=\"center\"><span style=\"color:$this->colorAlert\">The system is locked";
                if($showavailable){
                    $html .= "<br>You can add games to divisions that are below the limit but not unassign your Area from games<br>";
                }
                $html .= "</span><br>\n";
                foreach ( $assigned_list as $k => $v ) {
                    $tempassign = $assigned_list[$k];
                    if ( $tempassign ) {
                        if (isset($limit_list[$k]) && $limit_list[$k] != 'none') {
                            $html .= "For $k, you are assigned to <span style=\"color:$this->colorAlert\">$tempassign</span> games with a limit of <span style=\"color:$this->colorAlert\">$limit_list[$k]</span> games<br>\n";
                        }
                        else {
                            $html .= "For $k, you are assigned to <span style=\"color:$this->colorAlert\">$tempassign</span> games with no limit<br>\n";
                            $showavailable = true;
                        }
                        if ( ($assigned_list[$k] < $limit_list[$k]) || (!isset($limit_list[$k]))) {
                            $showavailable = false;
                        }
                    }
                }

				$html .= "</h3>\n";
			}
			elseif ( !$locked && count( $limit_list ) ) {
				$html .= "<h3 class=\"center\">\n";
				foreach ( $limit_list as $k => $v ) {
					$tempassign = $assigned_list[$k];
                    if ( $tempassign ) {
                        if ( $assigned_list[ $k ] && $limit_list[$k] != 'none' ) {
                            $html .= "For $k, you are assigned to <span style=\"color:$this->colorAlert\">$tempassign</span> games with a limit of <span style=\"color:$this->colorAlert\">$v</span> games<br>\n";
                            $oneatlimit = ($assigned_list[$k] >= $limit_list[$k]);
                        }
                        else {
                            $html .= "For $k, you are assigned to <span style=\"color:$this->colorAlert\">$tempassign</span> games with no limit<br>\n";
                            $oneatlimit = false;
                        }
                    }
				}
				if ( $oneatlimit ) {
				   $html .= "<br><span style=\"color:$this->colorAlert\">One or more of your divisions are at or above their limits<br>You will need to unassign games in that division before you can select additional games</span>\n";
				}
				$html .= "</h3>\n";
                $showavailable = true;
			}

            if($this->num_assigned || $showavailable) {
                $html .= "<h3 class=\"center\"> Shading change indicates different start times</h3>\n";
                $submitDisabled = (!$locked && (!$allatlimit && !empty($assigned_list)) || $showavailable) ? '' : ' disabled' ;

                $html .= "<form name=\"form1\" method=\"post\" action=\"$this->schedPath\">\n";

                $html .= "<div align=\"left\">";

                if (!$showavailable) {

                    $html .= "<h3 class=\"h3-btn\" >";

                    $html .= "<input type=\"hidden\" name=\"group\" value=\"$this->showgroup\">";
                    $html .= "<input class=\"btn btn-primary btn-xs right $submitDisabled\" type=\"submit\" name=\"Submit\" value=\"Submit\">\n";
                    $html .= "<div class='clear-fix'></div>";

                    $html .= "</h3>\n";
                } else {
                    $html .= "<h3 class=\"h3-btn\" >Available games :";

                    $html .= "<input type=\"hidden\" name=\"group\" value=\"$this->showgroup\">";
                    $html .= "<input class=\"btn btn-primary btn-xs right $submitDisabled\" type=\"submit\" name=\"Submit\" value=\"Submit\">\n";
                    $html .= "<div class='clear-fix'></div>";

                    $html .= "</h3>\n";
                    $html .= "<table class=\"sched_table\" >\n";
                    $html .= "<tr align=\"center\" bgcolor=\"$this->colorTitle\">";
                    $html .= "<th>Game No</th>";
                    $html .= "<th>Assign to $this->user</th>";
                    $html .= "<th>Date</th>";
                    $html .= "<th>Time</th>";
                    $html .= "<th>Field</th>";
                    $html .= "<th>Division</th>";
                    $html .= "<th>Pool</th>";
                    $html .= "<th>Home</th>";
                    $html .= "<th>Away</th>";
                    $html .= "<th>Referee Team</th>";
                    $html .= "</tr>";

                    for ($kant = 0; $kant < $kount; $kant++) {
                        if (($this->showgroup && $this->showgroup == $this->divisionAge($div[$kant])) || !$this->showgroup) {
                            if ($a_init != substr($home[$kant], 0, 1) && $a_init != substr($away[$kant], 0, 1) && !$ref_team[$kant] && $showavailable) {

                                if (!$testtime) {
                                    $testtime = $time[$kant];
                                } elseif ($testtime != $time[$kant]) {
                                    $testtime = $time[$kant];
                                    $tempcolor = $color1;
                                    $color1 = $color2;
                                    $color2 = $tempcolor;
                                }
                                $html .= "<tr align=\"center\" bgcolor=\"$color1\">";
                                $html .= "<td>$game_no[$kant]</td>";
                                $html .= "<td><input type=\"checkbox\" name=\"assign:$game_id[$kant]\" value=\"$game_id[$kant]\"></td>";
                                $html .= "<td>$date[$kant]</td>";
                                $html .= "<td>$time[$kant]</td>";
                                $html .= "<td>$field[$kant]</td>";
                                $html .= "<td>$div[$kant]</td>";
                                $html .= "<td>$pool[$kant]</td>";
                                $html .= "<td>$home[$kant]</td>";
                                $html .= "<td>$away[$kant]</td>";
                                $html .= "<td>&nbsp;</td>";
                                $html .= "</tr>\n";
                            }
                        }
                    }
                }
                $html .= "</table>";

                if($this->num_assigned){
                    $html .= "<h3>Games assigned to $this->user :</h3>\n";
                    if (empty($kount)) {
                        $html .= "<table class=\"sched_table\" >\n";
                        $html .= "<tr align=\"center\" bgcolor=\"$this->colorHighlight\">";
                        $html .= "<td>$this->user has no games assigned</td>";
                        $html .= "</tr>\n";
                    } else {
                        $html .= "<table class=\"sched_table\" >\n";
                        $html .= "<tr align=\"center\" bgcolor=\"$this->colorTitle\">\n";
                        $html .= "<th>Game No</th>\n";
                        $html .= "<th>Assigned</th>\n";
                        $html .= "<th>Date</th>\n";
                        $html .= "<th>Time</th>\n";
                        $html .= "<th>Field</th>\n";
                        $html .= "<th>Division</th>\n";
                        $html .= "<th>Pool</th>\n";
                        $html .= "<th>Home</th>\n";
                        $html .= "<th>Away</th>\n";
                        $html .= "<th>Referee Team</th>\n";
                        $html .= "</tr>\n";


                        $color1 = $this->colorGroup1;
                        $color2 = $this->colorGroup2;

                        for ($kant = 0; $kant < $kount; $kant++) {
                            if ($this->user == $ref_team[$kant]) {

                                if (!$testtime) {
                                    $testtime = $time[$kant];
                                } elseif ($testtime != $time[$kant]) {
                                    $testtime = $time[$kant];
                                    $tempcolor = $color1;
                                    $color1 = $color2;
                                    $color2 = $tempcolor;
                                }

                                $html .= "<tr align=\"center\" bgcolor=\"$color1\">";
                                $html .= "<td>$game_no[$kant]</td>";
                                if ($locked) {
                                    $html .= "<td>Locked</td>";
                                } else {
                                    $html .= "<td><input name=\"games:$game_id[$kant]\" type=\"checkbox\" value=\"$game_id[$kant]\" checked></td>";
                                }
                                $html .= "<td>$date[$kant]</td>";
                                $html .= "<td>$time[$kant]</td>";
                                $html .= "<td>$field[$kant]</td>";
                                $html .= "<td>$div[$kant]</td>";
                                $html .= "<td>$pool[$kant]</td>";
                                $html .= "<td>$home[$kant]</td>";
                                $html .= "<td>$away[$kant]</td>";
                                $html .= "<td>$ref_team[$kant]</td>";
                                $html .= "</tr>\n";
                            }
                        }
                        $html .= "</table>";
                    }
                }

                if(($showavailable && $kount) || $this->num_assigned) {
                    $html .= "<h3 class=\"center h3-btn\">" . $this->menuLinks($this->num_assigned) . "<input class=\"btn btn-primary btn-xs right $submitDisabled\" type=\"submit\" name=\"Submit\" value=\"Submit\"></h3>\n";
                    $html .= "<div class='clear-fix'></div>";
                }
                $html .= "</form>\n";

            } else {
                $html .= "<h3 class=\"center\">You have no games assigned.</h3><br>\n";
            }

			$_SESSION['locked'] = $locked;
	
		}
		else {
			$html .=  $this->errorCheck();
		}

        return $html;
          
    }
    private function menu()
    {
        $html =
<<<EOD
    <h3 align="center">
EOD;
        $html .= $this->menuLinks();

        $html .=
<<<EOD
    </h3>
EOD;
        
        return $html;
    }
    private function menuLinks()
    {
        $html =
<<<EOD
    <a href="$this->greetPath">Home</a>&nbsp;-&nbsp;
    <a href="$this->fullPath">View the full schedule</a>&nbsp;-&nbsp;
EOD;
        if ($this->num_assigned) {
            $html .=
<<<EOD
    <a href="$this->refsPath">Edit $this->user referee assignments</a>&nbsp;-&nbsp;
EOD;
        }

        $html .=
<<<EOD
    <a href="$this->endPath">Log off</a>
EOD;

        return $html;
    }
}
