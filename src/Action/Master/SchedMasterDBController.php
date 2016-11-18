<?php
namespace App\Action\Master;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractController;

class SchedMasterDBController extends AbstractController
{
    private $masterView;

	public function __construct(Container $container, SchedMasterView $masterView) {
		
		parent::__construct($container);
        
        $this->masterView = $masterView;

    }
    public function __invoke(Request $request, Response $response, $args)
    {
        if(!$this->isAuthorized()) {
            return $response->withRedirect($this->getBaseURL('logonPath'));
        };

        $this->logStamp($request);

        $request = $request->withHeader('user', $this->user);
        $request = $request->withHeader('event', $this->event);

        $this->masterView->handler($request, $response);
        $this->masterView->render($response);


        return $response;

    }
}


