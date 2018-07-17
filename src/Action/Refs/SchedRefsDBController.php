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

        $this->schedRefsView->handler($request, $response);

        if($request->isPost()) {
            $_SESSION['game_id'] = array_keys($_POST);
            $response =  $response->withRedirect($this->getBaseURL('editrefPath'));
        } else {
            $this->schedRefsView->render($response);
        }

        return $response;
    }
}


