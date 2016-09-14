<?php
namespace App\Action\AddRef;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;

class SchedAddRefController extends AbstractController
{
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Schedule greet page action dispatched");
        
        $content = array(
            'sched' => array (
                'greet' => $this->renderAddRef()
            )
        );        
        
        $this->view->render($response, 'sched.greet.html.twig', $content);
;
    }

    private function renderAddRef($response)
    {
        $html = null;
        
    
        return $html;
          
    }
}


