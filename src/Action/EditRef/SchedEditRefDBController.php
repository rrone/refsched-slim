<?php
namespace App\Action\EditRef;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;

class SchedEditRefDBController extends AbstractController
{
    // SchedulerRepository //
    private $sr;
	
	private $target_id;
    
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

        $this->logger->info("Schedule edit refs page action dispatched");

		$this->event = isset($_SESSION['event']) ?  $_SESSION['event'] : false;
        $this->rep = isset($_SESSION['unit']) ? $_SESSION['unit'] : null;
		$this->target_id = isset($_SESSION['target_id']) ? $_SESSION['target_id'] : null;

        if ( $request->isPost()) {
            if ($this->handleRequest($request)) {
            
				return $response->withRedirect($this->refsPath);
			}
        }
        
        $content = array(
            'view' => array (
                'content' => $this->renderEditRef(),
                'topmenu' => $this->menu(),
                'menu' => $this->menu(),
                'title' => $this->page_title,
				'dates' => $this->dates,
				'location' => $this->location,
				'description' =>  "Assign " . $this->rep . " Referees",
            )
        );        
        
        $this->view->render($response, 'sched.html.twig', $content);

    }
    private function handleRequest($request)
    {
		
		switch (count( $_POST ) > 3 ) {
			case 3:				
				$data = $request->getParsedBody();
		
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
			$html .=  "<center><h2>Enter Referee's First and Last name.</h2></center>\n" . 
				"<center><h2><span style=\"color:#FF0000\"><i>NOTE: Adding ?? or Area name is NOT helpful.</i></span></h2></center>";
   
			$games = $this->sr->getGames($projectKey);
			$numRefs = $this->sr->numberOfReferees($projectKey);
			
			if (count($games)){
				foreach ($games as $game){
					$day = date('D',strtotime($game->date));
					$time = date('H:i', strtotime($game->time));
					if ( $game->game_number == $target_game && ($game->assignor == $this->rep || $this->rep == 'Section 1') ) {
						$html .=  "      <form name=\"editref\" method=\"post\" action=\"$this->editrefPath\">\n";
						$html .=  "      <table class=\"sched_table\" width=\"100%\">\n";
						$html .=  "        <tr align=\"center\" bgcolor=\"$this->colorTitle\">";
						$html .=  "            <th>Game No.</th>";
						$html .=  "            <th>Day</th>";
						$html .=  "            <th>Time</th>";
						$html .=  "            <th>Location</th>";
						$html .=  "            <th>Division</th>";
						$html .=  "            <th>Referee Team</th>";
						$html .=  "            <th>Center</th>";
						$html .=  "            <th>AR1</th>";
						$html .=  "            <th>AR2</th>";
						if ($numRefs > 3){
							$html .=  "            <th>4th</th>";
						}
						$html .=  "            </tr>\n";
						$html .=  "            <tr align=\"center\" bgcolor=\"#00FF88\">";
						$html .=  "            <td>$game->game_number</td>";
						$html .=  "            <td>$day<br>$game->date</td>";
						$html .=  "            <td>$time</td>";
						$html .=  "            <td>$game->field</td>";
						$html .=  "            <td>$game->division</td>";
						$html .=  "            <td>$game->assignor</td>";
						$html .=  "            <td><input type=\"text\" name=\"cr\" size=\"15\" value=\"$game->cr\"></td>";
						$html .=  "            <td><input type=\"text\" name=\"ar1\" size=\"15\" value=\"$game->ar1\"></td>";
						$html .=  "            <td><input type=\"text\" name=\"ar2\" size=\"15\" value=\"$game->ar2\"></td>";
						if ($numRefs > 3){
							$html .=  "            <td><input type=\"text\" name=\"r4th\" size=\"15\" value=\"$game->r4th\"></td>";
						}
						$html .=  "            </tr>\n";
						$html .=  "            </table>\n";
						$html .=  "            <input class=\"btn btn-primary btn-xs right\" type=\"submit\" name=\"$game->id\" value=\"Update Assignments\">\n";
						$html .=  "      <div class='clear-fix'></div>";
						$html .=  "            </form>\n";
					}
				}                
            }
            else {
                $html .= "<center><h2>The matching game was not found or your Area was not assigned to it.<br>You might want to check the schedule and try again.</h2></center>\n";
            }

        }
        else {
           $html .= "<center><h2>You seem to have gotten here by a different path<br>\n";
           $html .= "You should go to the <a href=\"$this->refsPath\">Referee Edit Page</a></h2></center>";
        }
      
        return $html;
    
    }
    private function menu()
    {
        $html =
<<<EOD
		<h3 align="center"><a href="$this->greetPath">Home</a>&nbsp;-&nbsp;
EOD;

        if ( $this->rep == 'Section 1' ) {
           $html .=  "<a href=\"$this->masterPath\">Go to Section 1 schedule</a>&nbsp;-&nbsp;\n";
        }
        else {
           $html .=  "<a href=\"$this->refsPath\">Go to $this->rep schedule</a>&nbsp;-&nbsp;\n";
        }
		
		$html .=
<<<EOD
		<a href="$this->endPath">Log off</a></h3>
EOD;
        
        return $html;
    }
}


