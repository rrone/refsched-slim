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
    private $sr;
    
	public function __construct(Container $container, SchedulerRepository $repository) {
		
		parent::__construct($container);
        
        $this->sr = $repository;
		
    }
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : null;
         if (!$this->authed) {
            return $response->withRedirect($this->greetPath);
         }

        $this->logger->info("Schedule master page action dispatched");
        
        $content = array(
            'view' => array (
                'content' => $this->renderMaster(),
                'topmenu' => $this->menu(),
                'menu' => $this->menu(),
                'title' => $this->page_title,
				'dates' => $this->dates,
				'location' => $this->location,
				'description' => $this->rep . ' Schedule'
            )
        );        
        
        $this->view->render($response, 'sched.html.twig', $content);
    }

    private function renderMaster()
    {
        $html = null;
        
        $this->rep = isset($_SESSION['unit']) ? $_SESSION['unit'] : null;
        $event = isset($_SESSION['event']) ? $_SESSION['event'] : null;
		
		if (!empty($event)){
		
			$select_list = array( "None");
			$users = $this->sr->getUsers();

			foreach ($users as $user){
				$select_list[] = $user->name;
			}
			$select_list[] = 'Other';
	
			if ( $this->authed && $this->rep == 'Section 1' ) {
				
				$this->page_title = $event->name;
				$this->dates = $event->dates;
				$this->location = $event->location;
				$projectKey = $event->projectKey;
				$locked = $this->sr->getLocked($projectKey);
			
				$html .=  "  <form name=\"master_sched\" method=\"post\" action=\"$this->controlPath\">\n";
				$html .=  "      <input class=\"right\" type=\"submit\" name=\"Submit\" value=\"Submit\">\n";
				$html .=  "      <div class='clear-fix'></div>";
				
				$html .=  "      <table width=\"100%\">\n";
				$html .=  "        <tr align=\"center\" bgcolor=\"$this->colorTitle\">";
				$html .=  "            <th>Game No.</th>";
				$html .=  "            <th>Day</th>";
				$html .=  "            <th>Time</th>";
				$html .=  "            <th>Location</th>";
				$html .=  "            <th>Division</th>";
				$html .=  "            <th>Home</th>";
				$html .=  "            <th>Away</th>";
				$html .=  "            <th>Referee Team</th>";
				$html .=  "         </tr>\n";
				
				$games = $this->sr->getGames($projectKey);
				foreach($games as $game){
					$day = date('D',strtotime($game->date));
					$time = date('H:i', strtotime($game->time));
					if ( $game->assignor == "None" ) {
						$html .=  "            <tr align=\"center\" bgcolor=\"$this->colorOpen\">";
					}
					else {
						$html .=  "            <tr align=\"center\" bgcolor=\"$this->colorGroup\">";
					}
					$html .=  "            <td>$game->game_number</td>";
					$html .=  "            <td>$day<br>$game->date</td>";
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
				$html .=  "      </table>\n";
				$html .=  "      <input class=\"right\" type=\"submit\" name=\"Submit\" value=\"Submit\">\n";
				$html .=  "      <div class='clear-fix'></div>";
				$html .=  "      </form>\n";

			}
			else {
			   $html .=  "<center><h2>You probably want the <a href=\"$this->schedPath\">scheduling</a> page.</h2></center>";
			}
		}
		else {
			$html .=  $this->errorCheck();				
		}
      
        return $html;
          
    }
    private function menu()
    {
        $html =  "<h3 align=\"center\"><a href=\"$this->greetPath\">Go to main page</a>&nbsp;-&nbsp;\n";
        $html .=  "<a href=\"$this->fullPath\">Go to full schedule</a>&nbsp;-&nbsp;\n";
        $html .=  "<a href=\"$this->endPath\">Logoff</a></h3>\n";
      
        return $html;

    }
}


