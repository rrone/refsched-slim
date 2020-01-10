<?php
namespace App\Action\Master;


use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractController;

class SchedMasterController extends AbstractController
{
    private $masterView;

	public function __construct(Container $container, SchedMasterView $masterView) {
		
		parent::__construct($container);
        
        $this->masterView = $masterView;

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
        if(!$this->isAuthorized() || !$this->user->admin) {
            return $response->withRedirect($this->getBaseURL('greetPath'));
        }

        $this->logStamp($request);

        $request = $request->withAttributes([
            'user' => $this->user,
            'event' => $this->event
        ]);

        $this->masterView->handler($request, $response);
        $this->masterView->render($response);

        return $response;
    }
}


