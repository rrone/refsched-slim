<?php
namespace App\Action\User;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;

class UserUpdateDBController extends AbstractController
{
	private $sr;
	private $userName;
	
	public function __construct(Container $container, SchedulerRepository $repository) {
		
		parent::__construct($container);
		
		$this->sr = $repository;
		
    }
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : null;
		$this->rep = isset($_SESSION['unit']) ? $_SESSION['unit'] : null;        

         if (!$this->authed || $this->rep != 'Section 1') {
            return $response->withRedirect($this->logonPath);
         }

        $this->logger->info("Schedule user update page action dispatched");

        if ( $request->isPost() ) {
			$result = $this->handleRequest($request);
			
			if ($result == false){
				return $response->withRedirect($this->greetPath);
			}
		}

        $content = array(
            'view' => array (
                'users' => $this->renderUsers(),
                'action' => $this->userUpdatePath,
				'message' => $this->msg,
            )
        );        
        
        $this->view->render($response, 'user.html.twig', $content);
    }
	private function handleRequest($request)
	{
		if(in_array('btnUpdate', array_keys($_POST)) ) {
			$this->userName = $_POST['selectUser'];
			$userName = $this->userName;
			$pw = $_POST['passwordInput'];
			
			if (!empty($pw)){
				
				$userDb = $this->sr->getUserByName($userName);
		
				if(empty($userDb)) {
					$userData = array (
						'name' => $userName,
						'enabled' => false,
					);
				}
				else {
					$userData = array (
						'name' => $userDb->name,
						'enabled' => $userDb->enabled,
					);
				}
		
				$userData['hash'] = password_hash($pw, PASSWORD_BCRYPT);
				$userData['password'] = crypt( $pw, 11);
				
				$this->sr->setUser($userData);
				
				$this->msg = "$userName password has been updated.";
			}
			else {
				$this->msg = "Password may not be blank.";
			}
			
			return true;
			
		}
		elseif (in_array('btnCancel', array_keys($_POST)) ) {
			
			return false;
		}
	}
    private function renderUsers()
    {
		$users = $this->sr->getAllUsers();
		$this->msg = '';
		
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
