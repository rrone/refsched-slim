<?php
namespace App\Action\MedalRound;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractView;
use App\Action\SchedulerRepository;

class MedalRoundView extends AbstractView
{
    private $hide;
    private $show;

    public function __construct(Container $container, SchedulerRepository $schedulerRepository)
    {
        parent::__construct($container, $schedulerRepository);

        $this->sr = $schedulerRepository;
    }

    public function handler(Request $request, Response $response)
    {
        $this->user = $request->getAttribute('user');
        $this->event = $request->getAttribute('event');
        $this->hide = $request->getAttribute('hide');
        $this->show = $request->getAttribute('show');
    }

    public function render(Response $response)
    {
        $cont = null;

        if ($this->hide) {
            $cont = $this->renderHideMedalRound();
        } elseif ($this->show) {
            $cont = $this->renderShowMedalRound();
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

    public function renderHideMedalRound()
    {
        if (!empty($this->event) && $this->user->admin) {
            $projectKey = $this->event->projectKey;
            $this->sr->hideMedalRound($projectKey);
        }

        return null;
    }

    public function renderShowMedalRound()
    {
        if (!empty($this->event) && $this->user->admin) {
            $projectKey = $this->event->projectKey;
            $this->sr->showMedalRound($projectKey);
        }

        return null;
    }
}