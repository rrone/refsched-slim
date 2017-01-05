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

    public function __invoke(Request $request, Response $response, $args)
    {
//        $_GET = $request->getParams();
//
//        if(isset($_GET['id'])){
//            switch ($_GET['id']) {
//                case '':
//                case 'x':
//                    unset($_SESSION['param']);
//                    break;
//                default:
//                    $_SESSION['param'] = $_GET['id'];
//            }
//        }
//
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
