<?php
namespace App\Action\Lock;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractController;

class SchedLockDBController extends AbstractController
{
    private $lulView;

    public function __construct(Container $container, SchedLockView $lockView)
    {
        parent::__construct($container);

        $this->lulView = $lockView;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        parent::__invoke($request, $response, $args);

        $this->logStamp($request);

        $request = $request->withHeader('user', $this->user);
        $request = $request->withHeader('event', $this->event);

        $this->lulView->handler($request, $response);
        $this->lulView->renderLock();

        return $response->withRedirect($this->container->get('greetPath'));
    }
}


