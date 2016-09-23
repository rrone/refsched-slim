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

        $this->logger->info("Schedule full page action dispatched");
        
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
			
			$html .=  "      <table width=\"100%\">\n";
			$html .=  "        <tr align=\"center\" bgcolor=\"$this->colorTitle\">";
			$html .=  "            <th>Game No.</th>";
			$html .=  "            <th>Day</th>";
			$html .=  "            <th>Time</th>";
			$html .=  "            <th>Location</th>";
			$html .=  "            <th>Division</th>";
			$html .=  "            <th>Home</th>";
			$html .=  "            <th>Away</th>";
			$html .=  "            <th>Referee<br>Team</th>";
			$html .=  "         </tr>\n";
			foreach ($games as $game) {
				$day = date('D',strtotime($game->date));
				$time = date('H:i', strtotime($game->time));

				if ( $game->assignor == $this->rep ) {
					$html .=  "            <tr align=\"center\" bgcolor=\"$this->colorGroup\">";
				}
				else {
					$html .=  "            <tr align=\"center\" bgcolor=\"$this->colorNotGroup\">";
				}
				$html .=  "            <td>$game->game_number</td>";
				$html .=  "            <td>$day<br>$game->date</td>";
				$html .=  "            <td>$time</td>";
				$html .=  "            <td>$game->field</td>";
				$html .=  "            <td>$game->division</td>";
				$html .=  "            <td>$game->home</td>";
				$html .=  "            <td>$game->away</td>";
				$html .=  "            <td>$game->assignor</td>";
				$html .=  "            </tr>\n";
			}
			$html .=  "      </table>\n";

			$this->menu = $this->menu();
		}
		
        return $html;
          
    }
    private function menu()
    {
        $html =  "<h3 align=\"center\"><a href=\"$this->greetPath\">Go to main page</a>&nbsp;-&nbsp;\n";

        if ( $this->rep == 'Section 1' ) {
           $html .=  "<a href=\"$this->masterPath\">Schedule referee teams</a>&nbsp;-&nbsp;\n";
        }
        else {
           $html .=  "<a href=\"$this->schedPath\">Go to $this->rep schedule</a>&nbsp;-&nbsp;\n";
        }

        $html .=  "<a href=\"$this->endPath\">Logoff</a></h3>\n";
        
        return $html;
    }
}


