<?php
namespace App\Action\Full;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractController;

class SchedFullDBController extends AbstractController
{
    /* @var SchedFullView */
    private $fullView;

    public function __construct(Container $container, SchedFullView $fullView)
    {
        parent::__construct($container);

        $this->fullView = $fullView;
    }
    public function __invoke(Request $request, Response $response, $args)
    {
        parent::__invoke($request, $response, $args);

        $this->logStamp($request);

        $request = $request->withHeader('user', $this->user);
        $request = $request->withHeader('event', $this->event);

        $this->fullView->handler($request, $response);
        $this->fullView->render($response);

        return $response;
    }

}


