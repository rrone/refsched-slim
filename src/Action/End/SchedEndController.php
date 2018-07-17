<?php
namespace App\Action\End;

use Slim\Container;
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;
use App\Action\AbstractController;

class SchedEndController extends AbstractController
{
    public function __construct(Container $container) {

        parent::__construct($container);
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
        $this->isAuthorized();

        $this->logStamp($request);

//        $key = isset($_SESSION['param']) ? "?id=" . $_SESSION['param'] : '';
//        $resp = $response->withRedirect($this->getBaseURL('logonPath') . $key);
        $resp = $response->withRedirect($this->getBaseURL('logonPath'));

        if(isset($_SESSION)) {
            session_unset();

            session_destroy();
        }

        return $resp;
    }
}


