<?php
namespace App\Action\End;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;
use App\Action\SessionManager;

class SchedEndController extends AbstractController
{
    public function __construct(Container $container, SchedulerRepository $repository, SessionManager $sessionManager)
    {
        parent::__construct($container, $sessionManager);

        $this->sr = $repository;

    }
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Schedule end page action dispatched");

        $user = $this->sr->getUserByName($GLOBALS['user']);

        $this->tm->clearGlobals($user);

//        session_unset();
//
//        session_destroy();
        
        return $response->withRedirect($this->logonPath);
    }
}


