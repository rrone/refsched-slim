<?php
namespace App\Action\Lock;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;

class SchedLockDBController extends AbstractController
{
    public function __construct(Container $container, SchedulerRepository $repository)
    {

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

        $this->logger->info($this->logStamp() . ": Scheduler lock action dispatched");

        $this->renderLock();

        return $response->withRedirect($this->greetPath);

//        $content = array(
//            'view' => array (
//                'user' => $this->user,
//                'ulock' => $this->renderLock(),
//                'topmenu' => $this->menu(),
//                'menu' => $this->menu(),
//                'title' => $this->page_title,
//				'dates' => $this->dates,
//				'location' => $this->location,
//            )
//        );

//        $this->view->render($response, 'sched.ulock.html.twig', $content);
    }

    private function renderLock()
    {
        $html = null;
        $event = $this->event;

		if (!empty($event)) {
			$projectKey = $event->projectKey;
            $locked = $this->sr->getLocked($projectKey);

            if ( $locked ) {
               $html .= "<h3 align=\"center\">The schedule is already locked!</h3>\n";
            }
			elseif ( $this->user->admin) {
               $this->sr->lockProject($projectKey);
               $html .= "<h3 align=\"center\">The schedule has been locked!</h3>\n";
            }
        }
        elseif ( $this->user->admin) {
           $html .= "<h2 class=\"center\">You seem to have gotten here by a different path<br>\n";
           $html .= "You should go to the <a href=\"$this->masterPath\">Schedule Page</a></h2>";
        }
        elseif ( !$this->user->admin ) {
           $html .= "<h2 class=\"center\">You seem to have gotten here by a different path<br>\n";
           $html .= "You should go to the <a href=\"$this->schedPath\">Schedule Page</a></h2>";
        }
        else {
           $html .= "<h2 class=\"center\">You need to <a href=\"$this->logonPath\">logon</a> first.</h2>";
        }

        return $html;

    }
//    private function menu()
//    {
//        $html =
//<<<EOT
//      <h3 align="center"><a href="$this->greetPath">Go to main screen</a>&nbsp;-&nbsp;
//      <a href="$this->masterPath">Go to schedule</a>&nbsp;-&nbsp;
//      <a href="$this->endPath">Log off</a></h3>
//EOT;
//        return $html;
//    }
}


