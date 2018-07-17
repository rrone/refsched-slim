<?php
namespace App\Action\Logon;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractController;

class LogonDBController extends AbstractController
{
    /* @var LogonView */
    private $logonView;

    public function __construct(Container $container, LogonView $view)
    {

        parent::__construct($container);

        $this->logonView = $view;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function __invoke(Request $request, Response $response, $args)
    {

        $this->logonView->handler($request, $response);

        if ($this->isAuthorized()) {
            $this->logStamp($request);

            return $response->withRedirect($this->getBaseURL('greetPath'));
        } else {
            $this->logonView->render($response);

            return $response;
        }

    }
}
