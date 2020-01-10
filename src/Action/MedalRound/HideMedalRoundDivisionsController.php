<?php
namespace App\Action\MedalRound;


use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractController;

class HideMedalRoundDivisionsController extends AbstractController
{
    private $mrdView;

    public function __construct(Container $container, MedalRoundDivisionsView $medalRoundDivisionsView)
    {
        parent::__construct($container);

        $this->mrdView = $medalRoundDivisionsView;
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
        if(!$this->isAuthorized() || !$this->user->admin) {
            return $response->withRedirect($this->getBaseURL('greetPath'));
        }

        $this->logStamp($request);

        $request = $request->withAttributes([
            'user' => $this->user,
            'event' => $this->event,
            'divs' => false
        ]);

        $this->mrdView->handler($request, $response);
        $this->mrdView->render($response);

        return $response->withRedirect($this->getBaseURL('greetPath'));
    }
}


