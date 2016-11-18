<?php
namespace App\Action\EditRef;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractController;

class SchedEditRefDBController extends AbstractController
{
    /* @var SchedEditRefView */
    private $schedEditRefView;

    public function __construct(Container $container, SchedEditRefView $schedEditRefView)
    {
        parent::__construct($container);

        $this->schedEditRefView = $schedEditRefView;
    }
    public function __invoke(Request $request, Response $response, $args)
    {
        if(!$this->isAuthorized()) {
            return $response->withRedirect($this->getBaseURL('logonPath'));
        };

        $game_id = isset($_SESSION['game_id']) ? $_SESSION['game_id'] : null;

        $this->logStamp($request);

        $request = $request->withAttribute('user', $this->user);
        $request = $request->withAttribute('event', $this->event);
        $request = $request->withAttribute('game_id', $game_id);

        $this->schedEditRefView->handler($request, $response);
        if(!isset($_SESSION['game_id'])){
            return $response->withRedirect($this->getBaseURL('refsPath'));
        }
        $this->schedEditRefView->render($response);

        return $response;
    }
}


