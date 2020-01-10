<?php
namespace App\Action\Sched;


use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractController;

class SchedSchedController extends AbstractController
{
    private $schedSchedView;

    public function __construct(Container $container, SchedSchedView $schedSchedView) {
		
		parent::__construct($container);

        $this->schedSchedView = $schedSchedView;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     *
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        if(!$this->isAuthorized()) {
            return $response->withRedirect($this->getBaseURL('logonPath'));
        }

        $this->logStamp($request);

        $request = $request->withAttributes([
            'user' => $this->user,
            'event' => $this->event
        ]);

        $this->schedSchedView->handler($request, $response);
        $this->schedSchedView->render($response);

        return $response;

    }
}
