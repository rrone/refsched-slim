<?php
namespace App\Action\Logon;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;
use App\Action\SessionManager;
use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\Cookie;

class LogonDBController extends AbstractController
{
	private $users;
	private $events;
	private $enabled;

    /**
     * LogonDBController constructor.
     * @param Container $container
     * @param SchedulerRepository $repository
     * @param SessionManager $sessionManager
     */
    public function __construct(Container $container, SchedulerRepository $repository, SessionManager $sessionManager) {
		
		parent::__construct($container, $sessionManager);
		
		$this->sr = $repository;

		$this->users = $repository->getUsers();
		$this->events = $repository->getCurrentEvents();
		$this->enabled = $repository->getEnabledEvents();
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Logon page action dispatched");
       
        $request = $this->handleRequest($request);

        $this->vars = $this->tm->getSessionVars($request);

        $this->authed = $this->vars['authed'];

		if ($this->authed) {
            $response = $this->tm->setSessionUser($response, $this->vars['user']);
            return $response->withRedirect($this->greetPath);
        }
		else {
			$content = array(
				'events' => $this->sr->getCurrentEvents(),
				'content' => $this->renderLogon(),
				'message' => $this->msg,
//                'script' => $this->getJS()
			);
		  
			$this->view->render($response, 'logon.html.twig', $content);      
	
			return $response;
		}
    }

    /**
     * @param $request
     */
    private function handleRequest($request)
	{
        $this->vars = $this->tm->emptySessionVars();

        if ( $request->isPost() ) {

            $userName = isset($_POST['user']) ? $_POST['user'] : null;
            $user = $this->sr->getUserByName($userName);
            $pass = isset($_POST['passwd']) ? $_POST['passwd'] : null;

            $hash = isset($user) ? $user->hash : null;

            $this->authed = password_verify($pass, $hash);

            if ($this->authed) {
                $event = $this->sr->getEventByLabel($_POST['event']);
                $this->tm->setSessionVars($user, $event);

                $request = FigRequestCookies::set($request, Cookie::create('user', $userName));

                $this->msg = null;
            }
            else {
                $this->tm->clearGlobals($user);
                $this->msg = 'Unrecognized password for ' . $_POST['user'];
            }
        }

        return $request;
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
					<td width="50%"><div align="right">ARA or representative from: </div></td>
					<td width="50%"><select id="user" class="form-control left-margin" name="user">
EOD;
            foreach ($users as $user) {
                $html .= "<option>$user->name</option>";
            }

            $html .= <<<EOD
                			            </select></td>
				</tr>

				<tr>
					<td width="50%"><div align="right">Password: </div></td>
					<td><input id="passwd" class="form-control" type="password" name="passwd"></td>
				</tr>
			</table>
			<p>
            <input id="logon_btn" type="submit" type="button" class="btn btn-primary btn-xs active" name="Submit" value="Logon">      
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
//    protected function getJS()
//    {
//        $js = <<<JQS
//$("#logon_btn").on("click", function() {
//     var key = sessionStorage.setItem('user', document.getElementById('user').value);
//     var user = sessionStorage.getItem('user');
//
//     console.info('logon_btn: ' + user);
//});
//
//JQS;
//
//        return $js;
//    }
}
