<?php
namespace App\Action\Sched;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractController;

class SchedSchedDBController extends AbstractController
{
    private $schedSchedView;

    public function __construct(Container $container, SchedSchedView $schedSchedView) {
		
		parent::__construct($container);

        $this->schedSchedView = $schedSchedView;
    }
    public function __invoke(Request $request, Response $response, $args)
    {
        parent::__invoke($request, $response, $args);

        $this->logStamp($request);

        $request = $request->withHeader('user', $this->user);
        $request = $request->withHeader('event', $this->event);

        $this->schedSchedView->handler($request, $response);
        $this->schedSchedView->render($response);

        return $response;

    }
}
