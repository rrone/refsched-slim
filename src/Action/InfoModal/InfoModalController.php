<?php
namespace App\Action\InfoModal;

use App\Action\AbstractController;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class InfoModalController extends AbstractController
{
    protected $modalView;

    public function __construct(Container $container, InfoModalView $view)
    {
        parent::__construct($container);

        $this->modalView = $view;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function __invoke(Request $request, Response $response)
    {
        if(!$this->isAuthorized()) {
            return $response->withRedirect($this->getBaseURL('greetPath'));
        };

        $this->modalView->handler($request, $response);

        echo $this->modalView->render($response);

        return null;

    }
}