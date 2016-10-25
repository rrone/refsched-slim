<?php
namespace App\Action\Control;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;

class SchedControlDBController extends AbstractController
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
            
		$this->logger->info("Schedule control page action dispatched");

        $this->event = isset($_SESSION['event']) ? $_SESSION['event'] : null;
        $this->user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

        if (is_null($this->event) || is_null($this->user)) {
            return $response->withRedirect($this->logonPath);
        }

		if ( $request->isPost()) {
			$this->handleRequest($request);
		}
        
        $content = array(
            'view' => array (
                'rep' => $this->user,
                'content' => $this->renderControl(),
                'topmenu' => $this->topmenu,
                'menu' => $this->menu(),
                'title' => $this->page_title,
				'dates' => $this->dates,
				'location' => $this->location,
				'description' => $this->user . ': Referee Team Schedule',
            )
        );        
     
        $this->view->render($response, 'sched.control.html.twig', $content);

        return $response;

    }
    private function handleRequest($request)
    {

    }
    private function renderControl()
    {
        $html = null;
		
		$event = $this->event;
        
		if (!empty($event)){

    //         print_r($_POST);
            
            $this->page_title = $event->name;
            $this->dates = $event->dates;
            $this->location = $event->location;
            $projectKey = $event->projectKey;
    
            $games = $this->sr->getGames($projectKey);
            if (count($games) ) {
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
                foreach($games as $game){
                    $date = date('D, d M',strtotime($game->date));
					$time = date('H:i', strtotime($game->time));
                    if ( !empty($game->assignor) ) {
                        $html .= "<tr align=\"center\" bgcolor=\"$this->colorGroup\">";
                    } 
                    else {
                        $html .= "<tr align=\"center\" bgcolor=\"$this->colorOpen\">";
                    } 
                    $html .=  "<td>$game->game_number</td>";
                    $html .=  "<td>$date</td>";
                    $html .=  "<td>$time</td>";
                    $html .=  "<td>$game->field</td>";
                    $html .=  "<td>$game->division</td>";
                    $html .=  "<td>$game->pool</td>";
                    $html .=  "<td>$game->home</td>";
                    $html .=  "<td>$game->away</td>";
                    $html .= "<td>$game->assignor</td>";
                    $html .= "</tr>\n";
                }
                
                $html .= "</table>\n";
                $this->topmenu = $this->menu();
            }
            else {
                $this->topmenu = null;
            }
        }
          
        else {
            $html .= $this->errorCheck();
            $this->page_title = "Section 1 Referee Scheduler";
            $this->topmenu = null;
        }

        return $html;
          
    }
    private function menu()
    {
        $html =
<<<EOT
      <h3 align="center"><a href="$this->greetPath">Home</a>&nbsp;-&nbsp;
      <a href="$this->fullPath">View the full schedule</a>&nbsp;-&nbsp;
      <a href="$this->masterPath">Schedule referee teams</a>&nbsp;-&nbsp;
      <a href="$this->endPath">Log off</a></h3>
EOT;
        
        return $html;
    }
}


