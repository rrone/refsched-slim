<?php

namespace App\Action\NoEvents;

use Slim\Container;
use Slim\Http\Response;
use App\Action\AbstractView;
use App\Action\SchedulerRepository;

class NoEventsView extends AbstractView
{
    public function __construct(Container $container, SchedulerRepository $repository)
    {
        parent::__construct($container, $repository);

        $this->sr = $repository;
    }

    public function render(Response &$response)
    {
        $content = array(
            'events' => $this->sr->getCurrentEvents(),
        );

        $this->view->render($response, 'noevents.html.twig', $content);

        return $response;
    }
}