<?php
namespace App\Action\Logon;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\SchedulerRepository;
use App\Action\AbstractController;

use Dflydev\FigCookies\FigResponseCookies;

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
        $this->logger->info("Logon page action dispatched");

        $this->handleRequest($request);

		if (!$this->authed) {
            $content = array(
                'events' => $this->sr->getCurrentEvents(),
                'content' => $this->renderLogon(),
                'message' => $this->msg,
            );

            $this->view->render($response, 'logon.html.twig', $content);

            return $response;
		}
		else {

            return $response->withRedirect($this->greetPath);
		}
    }
	private function handleRequest($request)
	{
        $request = $this->tm->clearRequest($request);
        $this->authed = null;
        $data = null;
var_dump($request);die();
        if ( $request->isPost() ) {

            $event = isset($_POST['event']) ? $this->sr->getEventByLabel($_POST['event']) : null;

            $userName = isset($_POST['user']) ? $_POST['user'] : null;
            $user = $this->sr->getUserByName($userName);
            $pass = isset($_POST['passwd']) ? $_POST['passwd'] : null;

            $hash = isset($user) ? $user->hash : null;

            $this->authed = password_verify($pass, $hash);

            if ($this->authed) {
                $data = array (
                    'event' => $event,
                    'user' => $user
                );

                $this->msg = null;

                echo $this->tm->jwt($data);
            }
            else {
                $data['event'] = null;
                $data['user'] = null;

                $this->msg = 'Unrecognized password for ' . $_POST['user'];
            }
        }

        return null;
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
            <input id="frmLogon" type="submit" class="btn btn-primary btn-xs active" name="Submit" value="Logon">      
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
