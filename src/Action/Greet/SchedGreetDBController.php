<?php
namespace App\Action\Greet;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
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
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : null;

        if (!$this->authed) {

            return $response->withRedirect($this->container->get('logonPath'));
        }

        $this->event = isset($_SESSION['event']) ? $_SESSION['event'] : null;
        $this->user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

        if (is_null($this->event) || is_null($this->user)) {

            return $response->withRedirect($this->container->get('logonPath'));
        }

        $this->logStamp($request);

        $response = $response->withHeader('user', $this->user);
        $response = $response->withHeader('event', $this->event);

        $this->greetView->render($response);

        $response = $response->withoutHeader('user');
        $response = $response->withoutHeader('event');

        return $response;
    }
}


