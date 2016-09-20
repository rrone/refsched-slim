<?php
namespace App\Action\Lock;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;

class SchedLockDBController extends AbstractController
{
    // SchedulerRepository //
    private $sr;
    
	public function __construct(Container $container, SchedulerRepository $repository) {
		
		parent::__construct($container);
        
        $this->sr = $repository;
		
    }
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Schedule greet page action dispatched");
        
        $content = array(
            'sched' => array (
                'ulock' => $this->renderLock(),
                'menu' => $this->menu(),
                'title' => $this->page_title,
				'dates' => $this->dates,
				'location' => $this->location,
            )
        );        
        
        $this->view->render($response, 'sched.ulock.html.twig', $content);
    }

    private function renderLock()
    {
        $html = null;
        
        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : false;        
        $this->rep = isset($_SESSION['unit']) ? $_SESSION['unit'] : null;
        
		$event = isset($_SESSION['event']) ?  $_SESSION['event'] : false;
		if (!empty($event)) {
			$projectKey = $event->projectKey;
            $locked = $this->sr->getLocked($projectKey);
		
            if ( $locked ) {
               $html .= "<h3 align=\"center\">The schedule is already locked!</h3>\n";
            }
			elseif ( $this->authed && $this->rep == 'Section 1') {
               $this->sr->lockProject($projectKey); 
               $html .= "<h3 align=\"center\">The schedule has been locked!</h3>\n";
            }
        }
        elseif ( $this->authed && $rep == 'Section 1') {
           $html .= "<center><h2>You seem to have gotten here by a different path<br>\n";
           $html .= "You should go to the <a href=\"$this->masterPath\">Schedule Page</a></h2></center>";
        }
        elseif ( $this->authed ) {
           $html .= "<center><h2>You seem to have gotten here by a different path<br>\n";
           $html .= "You should go to the <a href=\"$this->schedPath\">Schedule Page</a></h2></center>";
        }
        elseif ( !$this->authed ) {
           $html .= "<center><h2>You need to <a href=\"$this->logonPath\">logon</a> first.</h2></center>";
        }
        else {
           $html .= "<center><h1>Something is not right</h1></center>";
        }    
        return $html;
          
    }
    private function menu()
    {
        $html =
<<<EOT
      <h3 align="center"><a href="$this->greetPath">Go to main screen</a>&nbsp;-&nbsp;
      <a href="$this->masterPath">Go to schedule</a>&nbsp;-&nbsp;
      <a href="$this->endPath">Logoff</a></h3>
EOT;
        return $html;
    }
}


