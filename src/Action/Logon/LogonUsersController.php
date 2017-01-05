<?php
namespace App\Action\Logon;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractController;

class LogonUsersController extends AbstractController
{
    private $logonView;

    public function __construct(Container $container, LogonView $logonView)
    {
        parent::__construct($container);

        $this->logonView = $logonView;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $eventSelect = array_keys($request->getParams());

        if(isset($eventSelect[0])) {
            $eventLabel = str_replace('_', ' ', $eventSelect[0]);

            $options = $this->logonView->selectedUsers($eventLabel);

            echo $options;
        }
    }
}
