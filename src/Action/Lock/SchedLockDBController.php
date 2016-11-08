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
        if(!$this->isAuthorized()) {
            return $response->withRedirect($this->container->get('logonPath'));
        };

        $this->logStamp($request);

        $request = $request->withHeader('user', $this->user);
        $request = $request->withHeader('event', $this->event);

        $this->lulView->handler($request, $response);
        $this->lulView->renderLock();

        return $response->withRedirect($this->container->get('greetPath'));
    }
}


