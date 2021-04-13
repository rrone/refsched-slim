<?php
namespace App\Action\MedalRound;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractView;
use App\Action\SchedulerRepository;

class MedalRoundDivisionsView extends AbstractView
{
    private $showDivs;

    public function __construct(Container $container, SchedulerRepository $schedulerRepository)
    {
        parent::__construct($container, $schedulerRepository);

        $this->sr = $schedulerRepository;
    }

    public function handler(Request $request, Response $response)
    {
        $this->user = $request->getAttribute('user');
        $this->event = $request->getAttribute('event');
        $this->showDivs = $request->getAttribute('divs');
    }

    public function render(Response $response)
    {
        if ($this->showDivs) {
            $this->renderShowMedalRoundDivs();
        } else {
            $this->renderHideMedalRoundDivs();
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

    public function renderHideMedalRoundDivs()
    {
        if (!empty($this->event) && $this->user->admin) {
            $projectKey = $this->event->projectKey;
            $this->sr->hideMedalRoundDivisions($projectKey);
        }

        return null;
    }

    public function renderShowMedalRoundDivs()
    {
        if (!empty($this->event) && $this->user->admin) {
            $projectKey = $this->event->projectKey;
            $this->sr->showMedalRoundDivisions($projectKey);
        }

        return null;
    }
}