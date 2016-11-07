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
    private $bottommenu;
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
            return $response->withRedirect($this->container->get('greetPath'));
         }

        $this->event = isset($_SESSION['event']) ? $_SESSION['event'] : null;
        $this->user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

        if (is_null($this->event) || is_null($this->user)) {
            return $response->withRedirect($this->container->get('logonPath'));
        }

        $this->logStamp($request);

        if ( count( $_GET ) ) {
		   $this->justOpen = array_key_exists( 'open', $_GET );
		}
		if ( $request->isPost()) {
			$this->handleRequest($request);
		}

        $content = array(
            'view' => array (
                'admin' => $this->user->admin,
                'content' => $this->renderMaster(),
                'topmenu' => $this->topmenu,
                'menu' => $this->bottommenu,
                'title' => $this->page_title,
				'dates' => $this->dates,
				'location' => $this->location,
				'description' => $this->user->name . ': Schedule Referee Teams'
            )
        );        
        
        $this->view->render($response, 'sched.html.twig', $content);

        return $response;

    }
    private function handleRequest($request)
    {
		if( $this->user->admin) {
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
		
			if ( $this->authed && $this->user->admin ) {
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

                $html .= "<h3 class=\"center\"> Green shading change indicates different start times</h3>\n";
				$html .=  "<form name=\"master_sched\" method=\"post\" action=\"$this->container->get('masterPath')\">\n";

                $html .= $this->menu();

				$html .=  "<table class=\"sched_table\" width=\"100%\">\n";
				$html .=  "<tr align=\"center\" bgcolor=\"$this->colorTitle\">";
				$html .=  "<th>Game No.</th>";
				$html .=  "<th>Date</th>";
				$html .=  "<th>Time</th>";
				$html .=  "<th>Field</th>";
				$html .=  "<th>Division</th>";
                $html .=  "<th>Pool</th>";
				$html .=  "<th>Home</th>";
				$html .=  "<th>Away</th>";
				$html .=  "<th>Referee Team</th>";
				$html .=  "</tr>\n";
				
				if($this->user->admin) {
                    $games = $this->sr->getGames($projectKey, '%', true);
                } else {
                    $games = $this->sr->getGames($projectKey);
                }

                $rowColor = $this->colorGroup1;
                $testtime = null;

				foreach($games as $game){
					if ( !$this->justOpen || ($this->justOpen && empty($game->assignor)) ) {
						$date = date('D, d M',strtotime($game->date));
						$time = date('H:i', strtotime($game->time));

                        if ( !$testtime ) {
                            $testtime = $time;
                        }
                        elseif ( $testtime != $time && !empty($game->assignor)) {
                            $testtime = $time;
                            switch ($rowColor) {
                                case $this->colorGroup1:
                                    $rowColor = $this->colorGroup2;
                                    break;
                                default:
                                    $rowColor = $this->colorGroup1;
                            }
                        }

                        if ( empty($game->assignor) ) {
							$html .=  "<tr align=\"center\" bgcolor=\"$this->colorOpenSlots\">";
						}
                        else {
                            $html .= "<tr align=\"center\" bgcolor=\"$rowColor\">";
                        }
						$html .=  "<td>$game->game_number</td>";
						$html .=  "<td>$date</td>";
						$html .=  "<td>$time</td>";
						$html .=  "<td>$game->field</td>";
						$html .=  "<td>$game->division</td>";
                        $html .=  "<td>$game->pool</td>";
						$html .=  "<td>$game->home</td>";
						$html .=  "<td>$game->away</td>";
						
						$html .=  "<td><select name=\"$game->id\">\n";
						foreach ($select_list as $user){
							if ($user == $game->assignor) {
								$html .=  "<option selected>$user</option>\n";
							}
							else {
								$html .=  "<option>$user</option>\n";
							}
						}
							
						$html .=  "</select></td>";
						$html .=  "</tr>\n";
					}
				}
				$html .=  "</table>\n";

                $html .= $this->menu();

				$html .=  "</form>\n";
				$this->topmenu = null;
                $this->bottommenu = null;
			}
			else {
				$html .=  "<h2 class=\"center\">You probably want the <a href=\"$this->container->get('schedPath')\">scheduling</a> page.</h2>";
				$this->topmenu = null;
                $this->bottommenu = $this->menu();
			}
		}
		else {
			$html .=  $this->errorCheck();				
		}
      
        return $html;
          
    }
    private function menu()
    {
        $unassigned = $this->sr->getUnassignedGames($this->event->projectKey);

        $html =  "<h3 align=\"center\" style=\"margin-top: 20px; line-height: 3em;\"><a href=\"$this->container->get('greetPath')\">Home</a>&nbsp;-&nbsp;\n";

        $html .= "<a href=\"$this->container->get('fullPath')\">View the full schedule</a> - \n";

        if (count($unassigned)) {
            if ($this->justOpen) {
                $html .= "<a href=\"$this->container->get('masterPath')\">View all referee teams</a> - \n";
            } else {
                $html .= "<a href=\"$this->container->get('masterPath')?open\">View open referee teams</a> - \n";
            }
        }
        $html .= "<a href=\"$this->container->get('schedPath')\">View Assignors</a>&nbsp;-&nbsp;\n";
		$html .= "<a href=\"$this->container->get('refsPath')\">Edit referee assignments</a> - \n";
        $html .=  "<a href=\"$this->container->get('endPath')\">Log off</a>";
        $html .=  "<input  class=\"btn btn-primary btn-xs right\" type=\"submit\" name=\"Submit\" value=\"Submit\">\n";
        $html .=  "<div class='clear-fix'></div>";
        $html .= "</h3>\n";
      
        return $html;

    }
}


