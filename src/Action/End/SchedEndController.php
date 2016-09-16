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
        
        $_SESSON['authed'] = FALSE;
        session_destroy();
        
        $content = array (
            "logon" => $this->logonPath  
        );
        
        $this->view->render($response, 'sched.end.html.twig', $content);
    }

    private function renderAddRef($response)
    {
        $html = null;
    
        return $html;
          
    }
}


