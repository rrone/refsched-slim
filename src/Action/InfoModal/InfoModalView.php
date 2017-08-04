<?php
namespace App\Action\InfoModal;

use App\Action\AbstractView;
use App\Action\SchedulerRepository;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class InfoModalView extends AbstractView
{
    public function __construct(Container $container, SchedulerRepository $schedulerRepository)
    {
        parent::__construct($container, $schedulerRepository);
    }

    public function handler(Request $request, Response $response)
    {
        // TODO: Implement handler() method.
    }

    public function render(Response &$response)
    {
        $content = array(
            'info' => null
        );

        $this->view->render($response, 'infomodal.html.twig', $content);

        return $response;
    }
}