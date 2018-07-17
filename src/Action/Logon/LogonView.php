<?php
namespace App\Action\Logon;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Action\AbstractView;
use App\Action\SchedulerRepository;

class LogonView extends AbstractView
{
    public function __construct(Container $container, SchedulerRepository $repository)
    {
        parent::__construct($container, $repository);

        $this->sr = $repository;
    }

    public function handler(Request $request, Response $response)
    {
        if ($request->isPost()) {
            $_POST = $request->getParsedBody();

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
            } else {
                //try master password
                $user = $this->sr->getUserByName('Admin');
                if(is_null($_SESSION['user'])){
                    $_SESSION['user'] = $user;
                }
                $hash = isset($user) ? $user->hash : null;
                $authed = password_verify($pass, $hash);

                if ($authed) {
                    $_SESSION['authed'] = true;
                    $_SESSION['admin'] = true;
                    $this->msg = null;
                } else {
                    $_SESSION['authed'] = false;
                    $_SESSION['admin'] = false;
                    $this->msg = 'Unrecognized password for ' . $_POST['user'];
                }
            }
        }

        return null;
    }

    public function render(Response &$response)
    {
        $key = isset($_SESSION['param']) ? $_SESSION['param'] : null;

        $content = array(
            'events' => $this->getCurrentEvents(),
            'content' => $this->renderView($key),
            'users' => $this->getBaseURL('logonUsersPath'),
            'message' => $this->msg,
        );

        $this->view->render($response, 'logon.html.twig', $content);

        return $response;
    }

    protected function renderView($key = null)
    {
        if (is_null($key)) {
            $enabled = $this->sr->getEnabledEvents();
        } else {
            $enabled = $this->sr->getEvent($key, true);
        }

        if (empty($enabled)) {
            return null;
        }

//        $projectKey = isset($enabled[0]) ? $enabled[0]->projectKey : $enabled->projectKey;
//        $users = $this->sr->getUsers($projectKey);

        $logonPath = $this->getBaseURL('logonPath');

        if (count($enabled) > 0) {

            $html = null;

            $html .= <<<EOD
                      <form name="form1" method="post" action="$logonPath">
        <div class="center">
			<table>
				<tr><td width="50%"><div class="right">Event: </div></td>
					<td width="50%">
						<select id="event" class="form-control left-margin" name="event">
EOD;
            foreach ($enabled as $option) {
                $html .= "<option>$option->label</option>";
            }

            $html .= <<<EOD
                						</select>
					</td>
				</tr>
		
				<tr>
					<td width="50%"><div class="right">Assignor: </div></td>
					<td width="50%"><select id="user" class="form-control left-margin" name="user">
EOD;
//            foreach ($users as $user) {
//                $html .= "<option>$user->name</option>";
//            }

            $html .= $this->selectedUsers(null);

            $html .= <<<EOD
                			            </select></td>
				</tr>

				<tr>
					<td width="50%"><div class="right">Password: </div></td>
					<td><input class="form-control" type="password" name="passwd"></td>
				</tr>
			</table>
			<p>
            <input type="submit" type="button" class="btn btn-primary btn-xs active" name="Submit" value="Logon">      
			</p>
        </div>
      </form>
EOD;
        } else {
            $html = "<div class=\"center no-content\">
                <h2>Rest easy...there are no events available to schedule.</h2>
                <h2>Go referee some matches yourself.</h2>
                </div>";
        }

        return $html;
    }

    public function selectedUsers($e)
    {
        if(empty($e)) {
            return "<option>Please select an event</option>\n";
        }

        $event = $this->sr->getEventByLabel($e);

        if(is_null($event)) {
            return "<option>Please select an event</option>\n";
        }

        $users = $this->sr->getUsers($event->projectKey);

        $options = null;
        foreach ($users as $user) {
            $options .= "<option>$user->name</option>\n";
        }

        return $options;
    }
}