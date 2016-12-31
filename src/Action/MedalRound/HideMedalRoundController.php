<?php
namespace App\Action\MedalRound;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractController;

class HideMedalRoundController extends AbstractController
{
    private $mrView;

    public function __construct(Container $container, MedalRoundView $medalroundView)
    {
        parent::__construct($container);

        $this->mrView = $medalroundView;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        if(!$this->isAuthorized() || !$this->user->admin) {
            return $response->withRedirect($this->getBaseURL('greetPath'));
        };

        $this->logStamp($request);

        $request = $request->withAttribute('user', $this->user);
        $request = $request->withAttribute('event', $this->event);
        $request = $request->withAttribute('hide', true);

        $this->mrView->handler($request, $response);
        $this->mrView->render($response);

        return $response->withRedirect($this->getBaseURL('greetPath'));
    }
}


