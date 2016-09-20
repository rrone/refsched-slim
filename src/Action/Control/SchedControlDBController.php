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
    
    // SchedulerRepository //
    private $sr;
    
	public function __construct(Container $container, SchedulerRepository $repository) {
		
		parent::__construct($container);
        
        $this->sr = $repository;
		
    }
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Schedule control page action dispatched");
        
        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : false;
        $this->rep = isset($_SESSION['unit']) ? $_SESSION['unit'] : null;
        
        $this->handleRequest($request);
        
        $content = array(
            'view' => array (
                'content' => $this->renderControl(),
                'topmenu' => $this->topmenu,
                'menu' => $this->menu(),
                'title' => $this->page_title,
				'dates' => $this->dates,
				'location' => $this->location,
				'description' => $this->rep . ' Schedule'
            )
        );        
     
        $this->view->render($response, 'sched.control.html.twig', $content);

    }
    private function handleRequest($request)
    {
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $_SERVER['HTTP_HOST'];
        $from_url = parse_url( $referer );
        $from = $from_url['path'];
  //      $html .= "<p>$from</p>\n";
        $url_ref = $this->masterPath;

        //only Section 1 may update
        if ( $this->authed && $_SERVER['REQUEST_METHOD'] == 'POST' && $from == $url_ref  && $this->rep == 'Section 1') {
            $data = $request->getParsedBody();
    
            $this->sr->updateAssignor($data);
        }
    }
    private function renderControl()
    {
        $html = null;
        
        $event = isset($_SESSION['event']) ? $_SESSION['event'] : null;
		
		if (!empty($event)){

    //         print_r($_POST);
            
            $this->page_title = $event->name;
            $this->dates = $event->dates;
            $this->location = $event->location;
            $projectKey = $event->projectKey;
    
            $games = $this->sr->getGames($projectKey);
            if (count($games) ) {
                $html .= "      <table width=\"100%\">\n";
                $html .= "        <tr align=\"center\" bgcolor=\"$this->colorTitle\">";                 
                $html .= "            <th>Game No.</th>";
                $html .= "            <th>Day</th>";
                $html .= "            <th>Time</th>";
                $html .= "            <th>Location</th>";
                $html .= "            <th>Div</th>";
                $html .= "            <th>Home</th>";
                $html .= "            <th>Away</th>";
                $html .= "            <th>Referee<br>Team</th>";
                $html .= "            </tr>\n";
                foreach($games as $game){
                    $day = date('D',strtotime($game->date));
                    if ( !empty($game->assignor) ) {
                        $html .= "            <tr align=\"center\" bgcolor=\"$this->colorGroup\">";
                    } 
                    else {
                        $html .= "            <tr align=\"center\" bgcolor=\"$this->colorOpen\">";
                    } 
                    $html .=  "            <td>$game->game_number</td>";
                    $html .=  "            <td>$day<br>$game->date</td>";
                    $html .=  "            <td>$game->time</td>";
                    $html .=  "            <td>$game->field</td>";
                    $html .=  "            <td>$game->division</td>";
                    $html .=  "            <td>$game->home</td>";
                    $html .=  "            <td>$game->visitor</td>";
                    $html .= "            <td>$game->assignor</td>";
                    $html .= "            </tr>\n";
                }
                
                $html .= "      </table>\n";
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
      <h3 align="center"><a href="$this->greetPath">Go to main page</a>&nbsp;-&nbsp;
      <a href="$this->fullPath">Go to full schedule</a>&nbsp;-&nbsp;
      <a href="$this->masterPath">Schedule referee teams</a>&nbsp;-&nbsp;
      <a href="$this->endPath">Logoff</a></h3>
EOT;
        
        return $html;
    }
}


