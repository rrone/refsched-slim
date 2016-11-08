<?php
namespace App\Action\Refs;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractController;

class SchedRefsDBController extends AbstractController
{
    /* @var SchedRefsView */
    private $schedRefsView;

    public function __construct(Container $container, SchedRefsView $schedRefsView)
    {
        parent::__construct($container);

        $this->schedRefsView = $schedRefsView;
    }
    public function __invoke(Request $request, Response $response, $args)
    {
        if(!$this->isAuthorized()) {
            return $response->withRedirect($this->container->get('logonPath'));
        };

        $this->logStamp($request);

        $request = $request->withHeader('user', $this->user);
        $request = $request->withHeader('event', $this->event);

        $this->schedRefsView->handler($request, $response);
        if(isset($_SESSION['game_id'])){
            return $response->withRedirect($this->container->get('editrefPath'));
        }
        $this->schedRefsView->render($response);

        return $response;

    }
}


