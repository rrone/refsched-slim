<?php
namespace App\Action\End;

use Slim\Container;
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;

class SchedEndController extends AbstractController
{
    public function __construct(Container $container, SchedulerRepository $repository) {

        parent::__construct($container);

        $this->sr = $repository;
    }
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
        $this->event = isset($_SESSION['event']) ? $_SESSION['event'] : null;

        $this->logStamp($request);

        session_unset();

        session_destroy();
        
        return $response->withRedirect($this->container->get('logonPath'));
    }
}


