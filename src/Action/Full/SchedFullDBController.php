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
        if(!$this->isAuthorized()) {
            return $response->withRedirect($this->container->get('logonPath'));
        };

        $this->logStamp($request);

        $request = $request->withAttribute('user', $this->user);
        $request = $request->withAttribute('event', $this->event);

        $this->fullView->handler($request, $response);
        $this->fullView->render($response);

        return $response;
    }

}


