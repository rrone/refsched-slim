<?php
namespace App\Action\NoEvents;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractController;

class NoEventsController extends AbstractController
{
    /* @var noEventsView */
    private $noEventsView;

	public function __construct(Container $container, NoEventsView $view) {
		
		parent::__construct($container);
		
        $this->noEventsView = $view;
    }
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->noEventsView->handler($request, $response);

        $this->noEventsView->render($response);

        return $response;
    }

}
