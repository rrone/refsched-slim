<?php
namespace App\Action\EditEvents;

use Slim\Container;
use App\Action\AbstractController;
use Slim\Http\Request;
use Slim\Http\Response;

class EditEventsController extends AbstractController
{
    /* @var EditEventsView */
    protected $editEventsView;

    public function __construct(Container $container, EditEventsView $editEventsView)
    {
        parent::__construct($container);

        $this->editEventsView = $editEventsView;
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
            return $response->withRedirect($this->getBaseURL('greetUrl'));
        }

        if($this->event->archived) {
            if(isset($_SERVER['HTTP_REFERER'])) {
                return $response->withRedirect($_SERVER['HTTP_REFERER']);
            } else {
                return $response->withRedirect($this->getBaseURL('greetUrl'));
            }
        }

        $this->logStamp($request);

        $request = $request->withAttributes([
            'user' => $this->user,
            'event' => $this->event
        ]);

        $this->editEventsView->handler($request, $response);

        $this->editEventsView->render($response);

        return $response;
    }
}