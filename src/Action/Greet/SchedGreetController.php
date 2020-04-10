<?php
namespace App\Action\Greet;


use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractController;

class SchedGreetController extends AbstractController
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
     *
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        if(!$this->isAuthorized()) {
            return $response->withRedirect($this->getBaseURL('logonPath'));
        }

        $params = $request->getParams();
        if (array_key_exists('asadmin', $params)) {
            $_SESSION['view'] = 'asadmin';
            return $response->withRedirect($this->getBaseURL('greetPath'));
        } elseif (array_key_exists('asuser', $params)) {
            $_SESSION['view'] = 'asuser';
            return $response->withRedirect($this->getBaseURL('greetPath'));
        }

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


