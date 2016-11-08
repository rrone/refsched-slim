<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 11/6/16
 * Time: 8:59 AM
 */

namespace App\Action\Logon;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractView;
use App\Action\SchedulerRepository;

class LogonView extends AbstractView
{
    private $users;
    private $enabled;

    public function __construct(Container $container, SchedulerRepository $repository)
    {
        parent::__construct($container, $repository);

        $this->sr = $repository;
    }
    public function handler(Request $request, Response $response)
    {
        if ($request->isPost()) {
            $userName = isset($_POST['user']) ? $_POST['user'] : null;
            $user = $this->sr->getUserByName($userName);
            $_SESSION['user'] = $user;
            $_SESSION['event'] = $this->sr->getEventByLabel($_POST['event']);

            // try user pass
            $pass = isset($_POST['passwd']) ? $_POST['passwd'] : null;
            $hash = isset($user) ? $user->hash : null;
            $authed = password_verify($pass, $hash);

            if ($authed) {
                $_SESSION['authed'] = true;
                $this->msg = null;
            }
            else {
                //try master password
                $user = $this->sr->getUserByName('Admin');
                $hash = isset($user) ? $user->hash : null;
                $authed = password_verify($pass, $hash);

                if ($authed) {
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

        return null;
    }
    public function render(Response &$response)
    {
        $content = array(
            'events' => $this->sr->getCurrentEvents(),
            'content' => $this->renderView(),
            'message' => $this->msg,
        );

        $this->view->render($response, 'logon.html.twig', $content);

        return $response;
    }
    protected function renderView()
    {
        $this->users = $this->sr->getUsers();
        $this->enabled = $this->sr->getEnabledEvents();

        $users = $this->users;
        $enabled = $this->enabled;
        $logonPath = $this->container->get('logonPath');

        if (count($enabled) > 0) {

            $html = <<<EOD
                      <form name="form1" method="post" action="$logonPath">
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
                <h2>Rest easy...there are no events are available to schedule.</h2>
                <h2>Go referee some games yourself.</h2>
            </div>
EOD;
        }

        return $html;
    }
}