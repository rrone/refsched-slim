<?php
namespace App\Action\MedalRound;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractView;
use App\Action\SchedulerRepository;

class MedalRoundAssignmentsView extends AbstractView
{
    private $showAssignments;

    public function __construct(Container $container, SchedulerRepository $schedulerRepository)
    {
        parent::__construct($container, $schedulerRepository);

        $this->sr = $schedulerRepository;
    }

    public function handler(Request $request, Response $response)
    {
        $this->user = $request->getAttribute('user');
        $this->event = $request->getAttribute('event');
        $this->showAssignments = $request->getAttribute('assignments');
    }

    public function render(Response &$response)
    {
        if ($this->showAssignments) {
            $this->renderShowMedalRoundAssignments();
        } else {
            $this->renderHideMedalRoundAssignments();
        }

        $content = array(
            'view' => array(
                'admin' => $this->user->admin,
                'content' => null,
                'title' => $this->page_title,
                'dates' => $this->dates,
                'location' => $this->location,
                'description' => "Schedule Status"
            )
        );

        $this->view->render($response, 'sched.html.twig', $content);
    }

    public function renderHideMedalRoundAssignments()
    {
        if (!empty($this->event) && $this->user->admin) {
            $projectKey = $this->event->projectKey;
            $this->sr->hideMedalRoundAssignments($projectKey);
        }

        return null;
    }

    public function renderShowMedalRoundAssignments()
    {
        if (!empty($this->event) && $this->user->admin) {
            $projectKey = $this->event->projectKey;
            $this->sr->showMedalRoundAssignments($projectKey);
        }

        return null;
    }
}