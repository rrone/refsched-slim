<?php
namespace App\Action\Full;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;

class SchedFullDBController extends AbstractController
{
    private $menu;
	private $justOpen;
    
    // SchedulerRepository //
    private $sr;
    
	public function __construct(Container $container, SchedulerRepository $repository) {
		
		parent::__construct($container);
        
        $this->sr = $repository;
		$this->justOpen = false;
		
    }
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : null;
        if (!$this->authed) {
            return $response->withRedirect($this->logonPath);
         }

        $this->logger->info("Schedule full page action dispatched");

		if ( count( $_GET ) ) {
		   $this->justOpen = array_key_exists( 'open', $_GET );
		}
        
        $content = array(
            'view' => array (
                'content' => $this->renderFull(),
                'topmenu' => $this->menu(),
                'menu' => $this->menu,
                'title' => $this->page_title,
				'dates' => $this->dates,
				'location' => $this->location,
				'description' =>  "Full Schedule"
            )
        );        

        $this->view->render($response, 'sched.html.twig', $content);
    }

    private function renderFull()
    {
        $html = null;
        
        $this->rep = isset($_SESSION['unit']) ? $_SESSION['unit'] : null;

		$event = isset($_SESSION['event']) ?  $_SESSION['event'] : false;
		if (!empty($event)) {
			$projectKey = $event->projectKey;
		
			$this->page_title = $event->name;
			$this->dates = $event->dates;
			$this->location = $event->location;

			$games = $this->sr->getGames($projectKey);
			$has4th = $this->sr->numberOfReferees($projectKey) > 3;
			
			$html .=  "<a href=\"$this->fullXlsPath\" class=\"btn btn-primary btn-xs right\">Export to Excel<i class=\"icon-white icon-circle-arrow-down\"></i></a>\n";
			$html .=  "<div class='clear-fix'></div>";

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
			$html .=  "            <th>Referee</th>";
			$html .=  "            <th>AR1</th>";
			$html .=  "            <th>AR2</th>";
			if ($has4th){
				$html .=  "            <th>4th</th>";
			}
			$html .=  "         </tr>\n";
			foreach ($games as $game) {
				if ( !$this->justOpen || ($this->justOpen && empty($game->cr)) ) {
					$date = date('D, d M',strtotime($game->date));
					$time = date('H:i', strtotime($game->time));
	
					if ( $game->assignor == $this->rep ) {
						$html .=  "            <tr align=\"center\" bgcolor=\"$this->colorGroup\">";
					}
					elseif ( !empty($game->assignor) ) {
						if ($this->rep == 'Section 1') {
							if (empty($game->cr)) {
								$html .=  "            <tr align=\"center\" bgcolor=\"$this->colorGroup\">";							
							}
							else {
								$html .=  "            <tr align=\"center\" bgcolor=\"$this->colorSuccess\">";															
							}
						}
						else {
							$html .=  "            <tr align=\"center\" bgcolor=\"$this->colorNotGroup\">";								
						}
					}
					else {
						$html .=  "            <tr align=\"center\" bgcolor=\"$this->colorOpen\">";
					}
					
					$html .=  "            <td>$game->game_number</td>";
					$html .=  "            <td>$date</td>";
					$html .=  "            <td>$time</td>";
					$html .=  "            <td>$game->field</td>";
					$html .=  "            <td>$game->division</td>";
					$html .=  "            <td>$game->home</td>";
					$html .=  "            <td>$game->away</td>";
					$html .= "            <td>$game->assignor</td>";
					$html .= "            <td>$game->cr</td>";
					$html .= "            <td>$game->ar1</td>";
					$html .= "            <td>$game->ar2</td>";
					if ($has4th){
						$html .= "            <td>$game->r4th</td>";
					}
					$html .=  "            </tr>\n";
				}
			}
			
			$html .=  "      </table>\n";

			$this->menu = $this->menu();
		}
		
        return $html;
          
    }
    private function menu()
    {
        $html =  "<h3 align=\"center\"><a href=\"$this->greetPath\">Home</a>&nbsp;-&nbsp;\n";
		if ($this->justOpen) {
			$html .=  "<a href=\"$this->fullPath\">View full schedule</a>&nbsp;-&nbsp;\n";
		}
		else {
			$html .=  "<a href=\"$this->fullPath?open\">View schedule with no referees</a>&nbsp;-&nbsp;\n";
		}
        if ( $this->rep == 'Section 1' ) {
           $html .=  "<a href=\"$this->masterPath\">Schedule referee teams</a>&nbsp;-&nbsp;\n";
		   $html .= "<a href=\"$this->refsPath\">Edit referees</a>&nbsp;-&nbsp;\n";
        }
        else {
           $html .=  "<a href=\"$this->schedPath\">Go to $this->rep schedule</a>&nbsp;-&nbsp;\n";
		   $html .= "<a href=\"$this->refsPath\">Edit $this->rep referees</a>&nbsp;-&nbsp;\n";
        }

        $html .=  "<a href=\"$this->endPath\">Log off</a></h3>\n";
        
        return $html;
    }
}


