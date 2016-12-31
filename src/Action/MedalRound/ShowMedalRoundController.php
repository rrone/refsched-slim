<?php
namespace App\Action\MedalRound;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractController;

class ShowMedalRoundController extends AbstractController
{
    private $mrView;

    public function __construct(Container $container, MedalRoundView $medalRoundView)
    {
        parent::__construct($container);

        $this->mrView = $medalRoundView;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        if(!$this->isAuthorized() || !$this->user->admin) {
            return $response->withRedirect($this->getBaseURL('greetPath'));
        };

        $this->logStamp($request);

        $request = $request->withAttribute('user', $this->user);
        $request = $request->withAttribute('event', $this->event);
        $request = $request->withAttribute('show', true);

        $this->mrView->handler($request, $response);
        $this->mrView->render($response);

        return $response->withRedirect($this->getBaseURL('greetPath'));
    }
}


