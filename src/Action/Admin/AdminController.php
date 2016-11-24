<?php
namespace App\Action\Admin;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
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
            return $response->withRedirect($this->getBaseURL('greetPath'));
        }

        $this->event = isset($_SESSION['event']) ?  $_SESSION['event'] : false;

        $this->logStamp($request);

        $request = $request->withAttribute('user', $this->user);
        $request = $request->withAttribute('event', $this->event);
        $response = $response->withHeader('adminPath', $this->getBaseURL('adminPath'));
        $result = $this->adminView->handler($request, $response);

        switch ($result) {
             case 'Cancel':

                 return $response->withRedirect($this->getBaseURL('greetPath'));

            case 'SchedTemplateExport':

                return $response->withRedirect($this->getBaseURL('schedTemplatePath'));

            case 'SchedImport':

                return $response->withRedirect($this->getBaseURL('schedImportPath'));

            case 'ExportLog':

                return $response->withRedirect($this->getBaseURL('logExportPath'));

            case 'EditGames':

                return $response->withRedirect($this->getBaseURL('editGamePath'));
        }

        $this->adminView->render($response);

        return $response;

    }
}
