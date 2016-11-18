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
        if(!$this->isAuthorized()) {
            return $response->withRedirect($this->getBaseURL('logonPath'));
        };

        $this->logStamp($request);

        $request = $request->withAttribute('user', $this->user);
        $request = $request->withAttribute('event', $this->event);

        $this->schedSchedView->handler($request, $response);
        $this->schedSchedView->render($response);

        return $response;

    }
}
