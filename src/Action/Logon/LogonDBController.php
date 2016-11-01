<?php
namespace App\Action\Logon;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;

class LogonDBController extends AbstractController
{
	private $users;
	private $events;
	private $enabled;

	public function __construct(Container $container, SchedulerRepository $repository) {
		
		parent::__construct($container);
		
		$this->sr = $repository;
		
		$this->users = $repository->getUsers();
		$this->events = $repository->getCurrentEvents();
		$this->enabled = $repository->getEnabledEvents();
		
    }
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->handleRequest($request);

		$this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : null;

        if ($this->authed) {
            $this->user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
            $this->logStamp($request);

            return $response->withRedirect($this->greetPath);
        }
		else {
			$content = array(
				'events' => $this->sr->getCurrentEvents(),
				'content' => $this->renderLogon(),
				'message' => $this->msg,
			);
		  
			$this->view->render($response, 'logon.html.twig', $content);      
	
			return $response;
		}
    }
	private function handleRequest($request)
	{
        if ($request->isPost()) {

            $userName = isset($_POST['user']) ? $_POST['user'] : null;
            $user = $this->sr->getUserByName($userName);
            $_SESSION['user'] = $user;
            $_SESSION['event'] = $this->sr->getEventByLabel($_POST['event']);

            // try user pass
            $pass = isset($_POST['passwd']) ? $_POST['passwd'] : null;
            $hash = isset($user) ? $user->hash : null;
            $this->authed = password_verify($pass, $hash);

            if ($this->authed) {
                $_SESSION['authed'] = true;
                $this->msg = null;
            }
            else {
                //try master password
                $user = $this->sr->getUserByName('Admin');
                $hash = isset($user) ? $user->hash : null;
                $this->authed = password_verify($pass, $hash);

                if ($this->authed) {
                    $_SESSION['authed'] = true;
                    $_SESSION['admin'] = true;
                    $this->msg = null;
                }
                else {
                    session_unset();
                    $this->msg = 'Unrecognized password for ' . $_POST['user'];
                }
            }
        }
	}

    /**
     * @return string
     */
    private function renderLogon()
    {
		$users = $this->users;
		$enabled = $this->enabled;

        if (count($enabled) > 0) {

            $html = <<<EOD
                      <form name="form1" method="post" action="$this->logonPath">
        <div align="center">
			<table>
				<tr><td width="50%"><div align="right">Event: </div></td>
					<td width="50%">
						<select class="form-control left-margin" name="event">
EOD;
            foreach ($enabled as $option) {
                $html .= "<option>$option->label</option>";
            }

            $html .= <<<EOD
                						</select>
					</td>
				</tr>
		
				<tr>
					<td width="50%"><div align="right">ARA or representative from: </div></td>
					<td width="50%"><select class="form-control left-margin" name="user">
EOD;
            foreach ($users as $user) {
                $html .= "<option>$user->name</option>";
            }

            $html .= <<<EOD
                			            </select></td>
				</tr>

				<tr>
					<td width="50%"><div align="right">Password: </div></td>
					<td><input class="form-control" type="password" name="passwd"></td>
				</tr>
			</table>
			<p>
            <input type="submit" type="button" class="btn btn-primary btn-xs active" name="Submit" value="Logon">      
			</p>
        </div>
      </form>
EOD;
        }
        else {
            $html = <<<EOD
            <div class="center no-content">
                <h2>No events are available to schedule.</h2>
            </div>
EOD;
        }

        return $html;
    }
}
