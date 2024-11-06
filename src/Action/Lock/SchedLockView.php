<?php
namespace App\Action\Lock;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractView;
use App\Action\SchedulerRepository;

class SchedLockView extends AbstractView
{
    private $lock;
    private $unlock;

    public function __construct(Container $container, SchedulerRepository $schedulerRepository)
    {
        parent::__construct($container, $schedulerRepository);

        $this->sr = $schedulerRepository;
    }

    public function handler(Request $request, Response $response)
    {
        $this->user = $request->getAttribute('user');
        $this->event = $request->getAttribute('event');
        $this->lock = $request->getAttribute('lock');
        $this->unlock = $request->getAttribute('unlock');
    }

    public function render(Response $response)
    {
        $cont = null;

        if($this->lock) {
            $cont = $this->renderLock();
        } elseif ($this->unlock) {
            $cont = $this->renderUnlock();
        }

        $content = array(
            'view' => array(
                'admin' => $this->user->admin,
                'content' => $cont,
                'title' => $this->page_title,
                'dates' => $this->dates,
                'location' => $this->location,
                'description' => "Schedule Status"
            )
        );

        $this->view->render($response, 'sched.html.twig', $content);
    }

    public function renderLock()
    {
        $html = null;

        if (!empty($this->event)) {
            $projectKey = $this->event->projectKey;
            //refresh event
            $this->event = $this->sr->getEvent($projectKey);
            $locked = $this->sr->getLocked($projectKey);

            if ($locked) {
                $html .= "<h3 class=\"center\">Assignments are already locked!</h3>\n";
            } elseif ($this->user->admin) {
                $this->sr->lockProject($projectKey);
                $html .= "<h3 class=\"center\">Assignments have been locked!</h3>\n";
            }
        }

        return $html;

    }

    public function renderUnlock()
    {
        $html = null;

        if (!empty($this->event) && $this->user->admin) {
            $projectKey = $this->event->projectKey;
            $locked = $this->sr->getLocked($projectKey);

            if (!$locked) {
                $html .= "<h3 class=\"center\">Assignments are already unlocked!</h3>\n";
            } elseif ($this->user->admin) {
                $this->sr->unlockProject($projectKey);
                $html .= "<h3 class=\"center\">Assignments are already unlocked!</h3>\n";
            }
        }

        return $html;

    }
}