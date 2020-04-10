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

            if (!$authed) {
                //try master password
                $usr = $this->sr->getUserByName('Super Admin');
                $hash = isset($usr) ? $usr->hash : null;
                $authed = password_verify($pass, $hash);
                $user = $user->admin ? $usr : $user;
            }

            $_SESSION['authed'] = true;
            $_SESSION['admin'] = $user->admin;
            $_SESSION['view'] = $user->admin ? 'asadmin' : 'asuser';
            $this->msg = $authed ? null : 'Unrecognized password for ' . $_POST['user'];
        }

        return null;
    }

    /**
     * @param Response $response
     * @return Response
     *
     */
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

    /**
     * @param null $key
     * @return null|string
     *
     */
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
				<tr><td class="width50"><div class="right">Event: </div></td>
					<td class="width50">
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
					<td class="width50"><div class="right">Assignor: </div></td>
					<td class="width50"><select id="user" class="form-control left-margin" name="user">
EOD;
//            foreach ($users as $user) {
//                $html .= "<option>$user->name</option>";
//            }

            $html .= $this->selectedUsers(null);

            $html .= <<<EOD
                			            </select></td>
				</tr>

				<tr>
					<td class="width50"><div class="right">Password: </div></td>
					<td><input class="form-control" type="password" name="passwd"></td>
				</tr>
			</table>
			<p>
            <input type="submit" class="btn btn-primary btn-xs active" name="Submit" value="Logon">      
			</p>
        </div>
      </form>
EOD;
        } else {
            $html = $this->sr->getEventMessage();
            if(empty($html)) {
                $html = "<div class=\"center no-content\">
                <h2>Rest easy...there are no events available to schedule.</h2>
                <h2>Go referee some matches yourself.</h2>
                </div>";
            }
        }

        return $html;
    }

    /**
     * @param $e
     * @return null|string
     */
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