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

		if ( $request->isPost() ) {
			$this->handleRequest($request);
		}
		        
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
				$html .= "      <table class=\"sched_table\" width=\"100%\">\n";
				$html .= "        <tr align=\"center\" bgcolor=\"$this->colorTitle\">";
				$html .= "            <th>Game No.</th>";
				$html .= "            <th>Day</th>";
				$html .= "            <th>Time</th>";
				$html .= "            <th>Location</th>";
				$html .= "            <th>Division</th>";
				$html .= "            <th>Home</th>";
				$html .= "            <th>Away</th>";
				$html .= "            <th>Referee Team</th>";
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
      <h3 align="center"><a href="$this->greetPath">Home</a>&nbsp;-&nbsp;
      <a href="$this->fullPath">View the full schedule</a>&nbsp;-&nbsp;
      <a href="$this->schedPath">Go to $this->rep schedule</a>&nbsp;-&nbsp;
      <a href="$this->refsPath">Edit $this->rep referees</a>&nbsp;-&nbsp;
      <a href="$this->endPath">Log off</a></h3>
EOD;
        return $html;   
    }
}

