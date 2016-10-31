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

		$this->event = isset($_SESSION['event']) ?  $_SESSION['event'] : false;
        $this->user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

        if (is_null($this->event) || is_null($this->user)) {
            return $response->withRedirect($this->logonPath);
        }

        $this->logger->info($this->logStamp() . ": Scheduler greet page dispatched");

        $this->handleRequest($request);

        $content = array(
            'view' => array (
                'admin' => $this->user->admin,
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

        return $response;
		
    }
	private function handleRequest($request)
	{
        if ($request->isPost()) {
        }

        return null;
	}
    private function renderAssign()
    {
        $html = null;
        
		if (!empty($this->event)) {
			$projectKey = $this->event->projectKey;

			$games = $this->sr->getGamesByRep($projectKey, $this->user);
			if (count($games)){
				$html .= "<h2  class=\"center\">You are currently scheduled for the following games</h2>\n";
				$html .= "<table class=\"sched_table\" width=\"100%\">\n";
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
				$html .= "</tr>\n";

				foreach($games as $game) {
					$date = date('D, d M',strtotime($game->date));
					$time = date('H:i', strtotime($game->time));
					$html .= "<tr align=\"center\" bgcolor=\"$this->colorGroup1\">";
					$html .= "<td>$game->game_number</td>";
					$html .= "<td>$date</td>";
					$html .= "<td>$time</td>";
					$html .= "<td>$game->field</td>";
					$html .= "<td>$game->division</td>";
                    $html .= "<td>$game->pool</td>";
					$html .= "<td>$game->home</td>";
					$html .= "<td>$game->away</td>";
					$html .= "<td>$game->assignor</td>";
					$html .= "</tr>\n";
				}					

				$html .= "</table>\n";
				$this->topmenu = $this->menu();
			}
			else {
				$html .= "<h2 class=\"center\">You do not currently have any games scheduled.</h2>\n";
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
      <a href="$this->schedPath">Go to !$this->user->nameschedule</a>&nbsp;-&nbsp;
      <a href="$this->refsPath">Edit !$this->user->namereferees</a>&nbsp;-&nbsp;
      <a href="$this->endPath">Log off</a></h3>
EOD;
        return $html;   
    }
}

