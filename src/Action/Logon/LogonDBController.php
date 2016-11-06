<?php
namespace App\Action\Logon;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;

class LogonDBController extends AbstractController
{
    /* @var LogonView */
    private $logonView;

	public function __construct(Container $container, SchedulerRepository $schedulerRepository, LogonView $view) {
		
		parent::__construct($container);
		
        $this->sr = $schedulerRepository;
        $this->logonView = $view;
    }
    public function __invoke(Request $request, Response $response, $args)
    {
        $response = $response->withHeader('logonPath', $this->logonPath);

        $this->logonView->handler($request, $response);

		$this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : null;

        if ($this->authed) {
            $this->user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
            $this->event = isset($_SESSION['event']) ? $_SESSION['event'] : null;
            $this->logStamp($request);

            return $response->withRedirect($this->greetPath);
        }
		else {
            $response = $this->logonView->render($response);

			return $response;
		}
    }

}
