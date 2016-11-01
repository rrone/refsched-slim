<?php
namespace App\Action\End;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;

class SchedEndController extends AbstractController
{
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
        $this->logStamp($request);

        session_unset();

        session_destroy();
        
        return $response->withRedirect($this->logonPath);
    }
}


