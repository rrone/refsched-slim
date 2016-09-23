<?php
namespace App\Action\Assign;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;

class SchedAssignDBController extends AbstractController
{
    private $topmenu;
    
    // SchedulerRepository //
    private $sr;
    
	public function __construct(Container $container, SchedulerRepository $repository) {
		
		parent::__construct($container);
        
        $this->sr = $repository;
		
    }
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : null;
         if (!$this->authed) {
            return $response->withRedirect($this->logonPath);
         }

        $this->logger->info("Schedule greet page action dispatched");

        $this->rep = isset($_SESSION['unit']) ? $_SESSION['unit'] : null;
		$this->event = isset($_SESSION['event']) ?  $_SESSION['event'] : false;

		$this->handleRequest($request);
        
        $content = array(
            'view' => array (
                'content' => $this->renderAssign(),
                'topmenu' => $this->topmenu,
                'title' => $this->page_title,
				'dates' => $this->dates,
				'location' => $this->location,
				'menu' => $this->menu(),
				'description' => 'Assign Assignors'
            )
        );        
        
        $this->view->render($response, 'sched.html.twig', $content);
		
    }
	private function handleRequest($request)
	{
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' && $this->rep != 'Section 1' ) {
			$projectKey = $this->event->projectKey;
			$locked = $this->sr->getLocked($projectKey);
			$msgHtml = null;

			//load limits if any or none
			$limits = $this->sr->getLimits($projectKey);
			if ( !count( $limits ) ) {
				$no_limit = true;
			}
			else {
				foreach($limits as $group){
					$limit_list[ $group->division ] = $group->limit;
				}
			}
			
			$array_of_keys = array_keys( $_POST );
			
			//parse the POST data
			$adds = [];
			$assign = [];
			foreach ($array_of_keys as $key){
				$change = explode(':',$key);
				switch  ($change[0]) {
					case 'assign':
						$adds[ $change[1] ] = $this->rep;
						break;
					case 'games':
						$assign[ $change[1] ] = $this->rep;
						break;
					default:
						continue;
				}
			}

			if ( !$locked ) {
				//remove drops if not locked
				$assigned_games = $this->sr->getGamesByRep($projectKey, $this->rep);
				if(count($assign) != count($assigned_games)){
					$removed = [];
					$unassign = [];
					foreach($assigned_games as $game) {
						if(!in_array($game->id, array_keys($assign)) ){
							$removed[$game->id] = $game;
							$unassign[$game->id] = '';
							$msgHtml .= "<p>You have <strong>removed</strong> your referee team from Game no. $game->game_number on $game->date at $game->time on $game->field</p>\n";
						}					
					}
					$this->sr->updateAssignor($unassign);	
					//initialize counting groups
					$assigned_games = $this->sr->getGamesByRep($projectKey, $this->rep);
					foreach ($assigned_games as $game) {
						$div = $this->divisionAge($game->division);
						$games_now[ $div ] = isset($games_now[ $div ]) ? $games_now[ $div ]++ : 0;
					}
				}
			}
			
			if ( count($assign)) {
				//Update based on add/returned games
				$added = [];
				$unavailable = [];
				$games = $this->sr->getGames($projectKey);		
				foreach($games as $game) {
					$div = $this->divisionAge($game->division);
					//ensure all indexes exist
					$games_now[ $div ] = isset($games_now[ $div ]) ? $games_now[$div] : 0;
					$atLimit[ $div ] = isset($atLimit[ $div ]) ? $atLimit[$div] : 0;;
					//if requested
					if(in_array($game->id, array_keys($adds)) ) {
						//and available
						if ( $game->assignor == '') {
							//and below the limit if there is one
							if ($games_now[$div] < $limit_list[$div] or $no_limit)  {
								//make the assignment
								$data = [ $game->id => $this->rep ];
								$this->sr->updateAssignor($data);
								$added[$game->id] = $game;
								$games_now[$div]++;
							}
							else {
								$atLimit[$div]++;
							}
						}
						else {
							$unavailable[$game_id] = $game;
						}
					}
				}

				$assigned_update = $this->sr->getGamesByRep($projectKey, $this->rep);
			}

//				$html .= "<p>You have <strong>scheduled</strong> Game no. $record[0] on $record[2], $record[1], $record[4] at $record[3]</p>\n";
//				$html .= "<p>You have <strong>scheduled</strong> Game no. $record[0] on $record[2], $record[1], $record[4] at $record[3]</p>\n";
//				$html .= "<p>You have <strong>not scheduled</strong> Game no. $record[0] on $record[2], $record[1], $record[4] at $record[3] because you are at your game limit!</p>\n";
//			   $html .= "<p>You have <strong>removed</strong> your referee team from Game no. $record[0] on $record[2], $record[1], $record[4] at $record[3]</p>\n";
//                       $html .= "<p>Your referee team has been <strong>removed</strong> from Game no. $record[0] on $record[2], $record[1], $record[4] at $record[3] because you are over the game limit.</p>\n";
//				$html .= "<p>I'm sorry, game no. $record[0] has been taken.</p>";
		}
	}
    private function renderAssign()
    {
        $html = null;
        
		if (!empty($this->event)) {
			$projectKey = $this->event->projectKey;
			$locked = $this->sr->getLocked($projectKey);
  
			$games = $this->sr->getGamesByRep($projectKey, $this->rep);
			if (count($games)){
				$html .= "<center><h2>You are currently scheduled for the following games</h2></center>\n";
				$html .= "      <table width=\"100%\">\n";
				$html .= "        <tr align=\"center\" bgcolor=\"$this->colorTitle\">";
				$html .= "            <th>Game No.</th>";
				$html .= "            <th>Day</th>";
				$html .= "            <th>Time</th>";
				$html .= "            <th>Location</th>";
				$html .= "            <th>Division</th>";
				$html .= "            <th>Home</th>";
				$html .= "            <th>Away</th>";
				$html .= "            <th>Referee<br>Team</th>";
				$html .= "            </tr>\n";

				foreach($games as $game) {
					$day = date('D',strtotime($game->date));
					$time = date('H:i', strtotime($game->time));
					$html .= "            <tr align=\"center\" bgcolor=\"$this->colorGroup\">";
					$html .= "            <td>$game->game_number</td>";
					$html .= "            <td>$day<br>$game->date</td>";
					$html .= "            <td>$time</td>";
					$html .= "            <td>$game->field</td>";
					$html .= "            <td>$game->division</td>";
					$html .= "            <td>$game->home</td>";
					$html .= "            <td>$game->away</td>";
					$html .= "            <td>$game->assignor</td>";
					$html .= "            </tr>\n";
				}					

				$html .= "      </table>\n";
				$this->topmenu = $this->menu();
			}
			else {
				$html .= "<center><h2>You do not currently have any games scheduled.</h2></center>\n";
				$this->topmenu = null;
			}
		}
		else {
			$html .= $this->errorCheck();
		}

		return $html;

    }
    private function menu()
    {
        $html = 
<<<EOD
      <h3 align="center"><a href="$this->greetPath">Go to main page</a>&nbsp;-&nbsp;
      <a href="$this->fullPath">Go to the full schedule</a>&nbsp;-&nbsp;
      <a href="$this->schedPath">Go to $this->rep schedule</a>&nbsp;-&nbsp;
      <a href="$this->refsPath">Edit $this->rep referees</a>&nbsp;-&nbsp;
      <a href="$this->endPath">Log off</a></h3>
EOD;
        return $html;   
    }
}

