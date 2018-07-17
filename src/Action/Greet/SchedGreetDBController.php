<?php
namespace App\Action\Greet;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractController;

class SchedGreetDBController extends AbstractController
{
    /* @var GreetView */
    private $greetView;

    public function __construct(Container $container, GreetView $greetView)
    {
        parent::__construct($container);

        $this->greetView = $greetView;

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

        $this->greetView->handler($request, $response);
        $this->greetView->render($response);

        return $response;
    }
}


