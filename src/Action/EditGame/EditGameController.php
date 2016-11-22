<?php
namespace App\Action\EditGame;

use Slim\Container;
use App\Action\AbstractController;
use Slim\Http\Request;
use Slim\Http\Response;

class EditGameController extends AbstractController
{
    /* @var EditGameView */
    protected $editGameView;

    public function __construct(Container $container, EditGameView $editGameView)
    {
        parent::__construct($container);

        $this->editGameView = $editGameView;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

        if (is_null($this->user) || !$this->user->admin) {
            return $response->withRedirect($this->getBaseURL('greetPath'));
        }

        $this->event = isset($_SESSION['event']) ?  $_SESSION['event'] : false;

        $this->logStamp($request);

        $request = $request->withAttribute('user', $this->user);
        $request = $request->withAttribute('event', $this->event);

        $this->editGameView->handler($request, $response);

        $this->editGameView->render($response);

        return $response;
    }
}