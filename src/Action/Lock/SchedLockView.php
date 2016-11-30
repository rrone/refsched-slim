<?php
namespace App\Action\Lock;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractView;
use App\Action\SchedulerRepository;

class SchedLockView extends AbstractView
{
    public function __construct(Container $container, SchedulerRepository $schedulerRepository)
    {
        parent::__construct($container, $schedulerRepository);

        $this->sr = $schedulerRepository;
    }

    public function handler(Request $request, Response $response)
    {
        $this->user = $request->getAttribute('user');
        $this->event = $request->getAttribute('event');
    }

    public function renderLock()
    {
        $html = null;

        if (!empty($this->event)) {
            $projectKey = $this->event->projectKey;
            $locked = $this->sr->getLocked($projectKey);

            if ($locked) {
                $html .= "<h3 class=\"center\">The schedule is already locked!</h3>\n";
            } elseif ($this->user->admin) {
                $this->sr->lockProject($projectKey);
                $html .= "<h3 class=\"center\">The schedule has been locked!</h3>\n";
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
                $html .= "<h3 class=\"center\">The schedule is already unlocked!</h3>\n";
            } elseif ($this->user->admin) {
                $this->sr->unlockProject($projectKey);
                $html .= "<h3 class=\"center\">The schedule has been unlocked!</h3>\n";
            }
        }

        return $html;

    }
}