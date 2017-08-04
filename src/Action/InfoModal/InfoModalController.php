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

    public function __invoke(Request $request, Response $response)
    {
        $this->modalView->handler($request, $response);

        $this->modalView->render($response);

        return $response;
    }
}