<?php
namespace App\Action\End;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;

class SchedEndController extends AbstractController
{
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Schedule end page action dispatched");

        session_unset();

        session_destroy();

        $userName = $GLOBALS['user'];
        $event = $GLOBALS['event'];
        $user = $this->sr->getUserByName($userName);
        $this->sr->setUserActive($user->id, $event->id, false);

        return $response->withRedirect($this->logonPath);
    }
}


