<?php
namespace App\Action\Admin;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractController;

class AdminController extends AbstractController
{
    /* @var AdminView */
    private AdminView $adminView;

	public function __construct(Container $container, AdminView $adminView) {
		
		parent::__construct($container);

        $this->adminView = $adminView;

    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $args): Response
    {
        if(!$this->isAuthorized() || !$this->user->admin) {
            return $response->withRedirect($this->getBaseURL('greetPath'));
        }

        $this->logStamp($request);

        $request = $request->withAttributes([
            'user' => $this->user,
            'event' => $this->event
        ]);

        $response = $response->withHeader('adminPath', $this->getBaseURL('adminPath'));
        $result = $this->adminView->handler($request, $response);

        switch ($result) {
             case 'Done':

                 return $response->withRedirect($this->getBaseURL('greetPath'));

            case 'SchedTemplateExport':

                return $response->withRedirect($this->getBaseURL('schedTemplatePath'));

            case 'SchedImport':

                return $response->withRedirect($this->getBaseURL('schedImportPath'));

            case 'ExportLog':

                return $response->withRedirect($this->getBaseURL('logExportPath'));
        }

        $this->adminView->render($response);

        return $response;

    }
}
