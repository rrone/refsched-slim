<?php
namespace App\Action\Full;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractController;

class SchedFullController extends AbstractController
{
    /* @var SchedFullView */
    private $fullView;

    public function __construct(Container $container, SchedFullView $fullView)
    {
        parent::__construct($container);

        $this->fullView = $fullView;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        if(!$this->isAuthorized()) {
            return $response->withRedirect($this->getBaseURL('logonPath'));
        };

        $this->logStamp($request);

        $request = $request->withAttributes([
            'user' => $this->user,
            'event' => $this->event
        ]);

        $this->fullView->handler($request, $response);
        $this->fullView->render($response);

        return $response;
    }

}


