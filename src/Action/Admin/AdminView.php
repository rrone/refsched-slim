<?php
namespace App\Action\Admin;

use App\Action\AbstractView;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\SchedulerRepository;

class AdminView extends AbstractView
{
	public function __construct(Container $container, SchedulerRepository $repository)
    {
        parent::__construct($container, $repository);

		$this->sr = $repository;
    }
	public function handler(Request $request, Response $response)
	{
        $this->user = $request->getHeader('user')[0];
        $event = $request->getHeader('event')[0];

        if ( $request->isPost() ) {
            if (in_array('btnUpdate', array_keys($_POST))) {
                $userName = $_POST['selectUser'];
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

                if(!empty($_POST['logNote'])) {
                    $projectKey = !is_null($event) ? $event->projectKey : '';
                    $msg = $this->user->name . ': ' . $_POST['logNote'];
                    $this->sr->logInfo($projectKey, $msg);
                }
            } else {
                $this->msg = null;
            }
        }

		return null;

	}
	public function render(Response &$response)
    {
        $adminPath = $response->getHeader('adminPath')[0];

        $content = array(
            'view' => array (
                'admin' => $this->user->admin,
                'users' => $this->renderUsers(),
                'action' => $adminPath,
                'message' => $this->msg,
                'messageStyle' => $this->msgStyle,
            )
        );

        $this->view->render($response, 'admin.html.twig', $content);

        return null;
    }
    protected function renderUsers()
    {
		$users = $this->sr->getAllUsers();

		$selectOptions = null;
		foreach($users as $user) {
			if ($user->name == $this->user->name) {
				$selectOptions .= "<option selected>$user->name</option>\n";
			}
			else {
				$selectOptions .= "<option>$user->name</option>\n";
			}
		}
		return $selectOptions;
    }
}
