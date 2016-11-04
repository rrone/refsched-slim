<?php
namespace App\Action\Admin;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;

class AdminController extends AbstractController
{
	private $userName;
	
	public function __construct(Container $container, SchedulerRepository $repository) {
		
		parent::__construct($container);
		
		$this->sr = $repository;
		
    }
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

        if (is_null($this->user) || !$this->user->admin) {
            return $response->withRedirect($this->greetPath);
        }

        $this->event = isset($_SESSION['event']) ?  $_SESSION['event'] : false;

        $this->logStamp($request);

        $result = $this->handleRequest($request);

        switch ($result) {
             case 'Cancel':

                 return $response->withRedirect($this->greetPath);

            case 'SchedTemplateExport':

                return $response->withRedirect($this->schedTemplatePath);

            case 'SchedImport':

                return $response->withRedirect($this->schedImportPath);

            case 'ExportLog':

                return $response->withRedirect($this->logExportPath);
        }

        $content = array(
            'view' => array (
                'admin' => $this->user->admin,
                'users' => $this->renderUsers(),
                'action' => $this->adminPath,
				'message' => $this->msg,
                'messageStyle' => $this->msgStyle,
            )
        );        

        $this->view->render($response, 'admin.html.twig', $content);

        return $response;

    }
	private function handleRequest($request)
	{
        if ( $request->isPost() ) {
            if (in_array('btnUpdate', array_keys($_POST))) {
                $this->userName = $_POST['selectUser'];
                $userName = $this->userName;
                $pw = $_POST['passwordInput'];

                if (!empty($pw)) {

                    $user = $this->sr->getUserByName($userName);

                    if (is_null($user)) {
                        $userData = array(
                            'name' => $userName,
                            'enabled' => false,
                        );
                    } else {
                        $userData = array(
                            'name' => $user->name,
                            'enabled' => $user->enabled,
                        );
                    }

                    $userData['hash'] = password_hash($pw, PASSWORD_BCRYPT);

                    $this->sr->setUser($userData);

                    $this->msg = "$userName password has been updated.";
                    $this->msgStyle = "color:#000000";
                } else {
                    $this->msg = "Password may not be blank.";
                    $this->msgStyle = "color:#FF0000";
                }

                return 'Update';

            } elseif (in_array('btnCancel', array_keys($_POST))) {

                return 'Cancel';

            } elseif (in_array('btnExport', array_keys($_POST))) {

                $this->msg = null;

                return 'SchedTemplateExport';

            } elseif (in_array('btnImport', array_keys($_POST))) {

                $this->msg = null;

                return 'SchedImport';

            } elseif (in_array('btnExportLog', array_keys($_POST))) {

                $this->msg = null;

                return 'ExportLog';

            } elseif (in_array('btnLogItem', array_keys($_POST))) {

                $projectKey = !is_null($this->event) ? $this->event->projectKey : '' ;
                $msg = $this->user->name . ': ' . $_POST['logNote'];
                $this->sr->logInfo($projectKey, $msg);
            } else {
                $this->msg = null;
            }
        }

		return null;

	}
    private function renderUsers()
    {
		$users = $this->sr->getAllUsers();

		$selectOptions = null;
		foreach($users as $user) {
			if ($user->name == $this->userName) {
				$selectOptions .= "<option selected>$user->name</option>\n";
			}
			else {
				$selectOptions .= "<option>$user->name</option>\n";
			}
		}
		return $selectOptions;
    }
}
