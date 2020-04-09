<?php
namespace App\Action\MedalRound;


use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractController;

class ShowMedalRoundAssignmentsController extends AbstractController
{
    private $mraView;

    public function __construct(Container $container, MedalRoundAssignmentsView $medalRoundAssignmentsView)
    {
        parent::__construct($container);

        $this->mraView = $medalRoundAssignmentsView;
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
            'assignments' => true,
        ]);

        $this->mraView->handler($request, $response);
        $this->mraView->render($response);

        return $response->withRedirect($this->getBaseURL('greetPath'));
    }
}


