<?php
namespace App\Action\Admin;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;

class AdminController extends AbstractController
{
    /* @var AdminView */
    private $adminView;

	public function __construct(Container $container, AdminView $adminView) {
		
		parent::__construct($container);

        $this->adminView = $adminView;

    }
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

        if (is_null($this->user) || !$this->user->admin) {
            return $response->withRedirect($this->container->get('greetPath'));
        }

        $this->event = isset($_SESSION['event']) ?  $_SESSION['event'] : false;

        $this->logStamp($request);

        $request = $request->withHeader('user', $this->user);
        $request = $request->withHeader('event', $this->event);
        $response = $response->withHeader('adminPath', $this->container->get('adminPath'));
        $result = $this->adminView->handler($request, $response);

        switch ($result) {
             case 'Cancel':

                 return $response->withRedirect($this->container->get('greetPath'));

            case 'SchedTemplateExport':

                return $response->withRedirect($this->container->get('schedTemplatePath'));

            case 'SchedImport':

                return $response->withRedirect($this->container->get('schedImportPath'));

            case 'ExportLog':

                return $response->withRedirect($this->container->get('logExportPath'));
        }

        $this->adminView->render($response);

        return $response;

    }
}
