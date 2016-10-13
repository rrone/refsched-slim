<?php
namespace App\Action\Master;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;

class SchedMasterDBController extends AbstractController
{
    // SchedulerRepository //
	private $topmenu;
	private $justOpen;
    
	public function __construct(Container $container, SchedulerRepository $repository) {
		
		parent::__construct($container);
        
        $this->sr = $repository;
		$this->justOpen = false;
		
    }
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : null;
         if (!$this->authed) {
            return $response->withRedirect($this->greetPath);
         }

        $this->logger->info("Schedule master page action dispatched");

        $this->event = isset($_SESSION['event']) ? $_SESSION['event'] : null;
        $this->rep = isset($_SESSION['unit']) ? $_SESSION['unit'] : null;

        if (is_null($this->event) || is_null($this->rep)) {
            return $response->withRedirect($this->logonPath);
        }

		if ( count( $_GET ) ) {
		   $this->justOpen = array_key_exists( 'open', $_GET );
		}
		if ( $request->isPost()) {
			$this->handleRequest($request);
		}

        $content = array(
            'view' => array (
                'rep' => $this->rep,
                'content' => $this->renderMaster(),
                'topmenu' => $this->topmenu,
                'menu' => $this->menu(),
                'title' => $this->page_title,
				'dates' => $this->dates,
				'location' => $this->location,
				'description' => $this->rep . ': Schedule Referee Teams'
            )
        );        
        
        $this->view->render($response, 'sched.html.twig', $content);

        return $response;

    }
    private function handleRequest($request)
    {
		if( $this->rep == 'Section 1') {
			//only Section 1 may update

			$data = $request->getParsedBody();
	
			$this->sr->updateAssignor($data);
		}
    }
    private function renderMaster()
    {
        $html = null;
		$event = $this->event;
		
		if (!empty($event)){
		
			if ( $this->authed && $this->rep == 'Section 1' ) {
				$select_list = array( '' );
				$users = $this->sr->getUsers();
					
				foreach ($users as $user){
					$select_list[] = $user->name;
				}
				$select_list[] = 'Other';
					
				$this->page_title = $event->name;
				$this->dates = $event->dates;
				$this->location = $event->location;
				$projectKey = $event->projectKey;

				$html .=  "  <form name=\"master_sched\" method=\"post\" action=\"$this->masterPath\">\n";
				$html .=  "      <input  class=\"btn btn-primary btn-xs right\" type=\"submit\" name=\"Submit\" value=\"Submit\">\n";
				$html .=  "      <div class='clear-fix'></div>";
				
				$html .=  "      <table class=\"sched_table\" width=\"100%\">\n";
				$html .=  "        <tr align=\"center\" bgcolor=\"$this->colorTitle\">";
				$html .=  "            <th>Game No.</th>";
				$html .=  "            <th>Date</th>";
				$html .=  "            <th>Time</th>";
				$html .=  "            <th>Field</th>";
				$html .=  "            <th>Division</th>";
				$html .=  "            <th>Home</th>";
				$html .=  "            <th>Away</th>";
				$html .=  "            <th>Referee Team</th>";
				$html .=  "         </tr>\n";
				
				$games = $this->sr->getGames($projectKey);
				foreach($games as $game){
					if ( !$this->justOpen || ($this->justOpen && empty($game->assignor)) ) {
						$date = date('D, d M',strtotime($game->date));
						$time = date('H:i', strtotime($game->time));
						if ( empty($game->assignor) ) {
							$html .=  "            <tr align=\"center\" bgcolor=\"$this->colorOpen\">";
						}
						else {
							$html .=  "            <tr align=\"center\" bgcolor=\"$this->colorGroup\">";
						}
						$html .=  "            <td>$game->game_number</td>";
						$html .=  "            <td>$date</td>";
						$html .=  "            <td>$time</td>";
						$html .=  "            <td>$game->field</td>";
						$html .=  "            <td>$game->division</td>";
						$html .=  "            <td>$game->home</td>";
						$html .=  "            <td>$game->away</td>";
						
						$html .=  "            <td><select name=\"$game->id\">\n";
						foreach ($select_list as $user){
							if ($user == $game->assignor) {
								$html .=  "               <option selected>$user</option>\n";
							}
							else {
								$html .=  "               <option>$user</option>\n";
							}
						}
							
						$html .=  "            </select></td>";
						$html .=  "            </tr>\n";
					}
				}
				$html .=  "      </table>\n";
				$html .=  "      <input class=\"btn btn-primary btn-xs right\" type=\"submit\" name=\"Submit\" value=\"Submit\">\n";
				$html .=  "      <div class='clear-fix'></div>";
				$html .=  "      </form>\n";
				$this->topmenu = $this->menu();

			}
			else {
				$html .=  "<h2 class=\"center\">You probably want the <a href=\"$this->schedPath\">scheduling</a> page.</h2>";
				$this->topmenu = null;
			}
		}
		else {
			$html .=  $this->errorCheck();				
		}
      
        return $html;
          
    }
    private function menu()
    {
        $html =  "<h3 align=\"center\"><a href=\"$this->greetPath\">Home</a>&nbsp;-&nbsp;\n";
        $html .=  "<a href=\"$this->fullPath\">View the full schedule</a>&nbsp;-&nbsp;\n";
		if ($this->justOpen) {
			$html .=  "<a href=\"$this->masterPath\">Select all referee teams</a>&nbsp;-&nbsp;\n";
		}
		else {
			$html .=  "<a href=\"$this->masterPath?open\">Select open referee teams</a>&nbsp;-&nbsp;\n";
		}
		$html .= "<a href=\"$this->refsPath\">Edit referees</a>&nbsp;-&nbsp;\n";
        $html .=  "<a href=\"$this->endPath\">Log off</a></h3>\n";
      
        return $html;

    }
}


