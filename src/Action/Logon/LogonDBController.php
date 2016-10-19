<?php
namespace App\Action\Logon;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\AbstractController;
use App\Action\SchedulerRepository;
use Firebase\JWT\JWT;
use Tuupola\Base62;
use Dflydev\FigCookies\SetCookie;
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

        $jwt = $this->handleRequest($request);

		if (!empty($this->authed)) {
            $response = FigResponseCookies::set($response, SetCookie::create('token')
                ->withValue($jwt)
            );

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
        $jwt = null;
        $this->authed = null;

        if ( $request->isPost() ) {

            $userName = isset($_POST['user']) ? $_POST['user'] : null;
            $user = $this->sr->getUserByName($userName);
            $pass = isset($_POST['passwd']) ? $_POST['passwd'] : null;
            $event = isset($_POST['event']) ? $_POST['event'] : null;

            $hash = isset($user) ? $user->hash : null;

            $this->authed = password_verify($pass, $hash);

            if ($this->authed) {
                $tokenId    = Base62::encode(random_bytes(16));

                $issuedAt   = time();
                $notBefore  = $issuedAt - 10;             //Adding 10 seconds
                $expire     = $notBefore + 3600;            // Adding 1 hour
                $serverName = $_SERVER['SERVER_NAME'];      // Retrieve the server name from config file

                /*
                 * Create the token as an array
                 */
                $data = [
                    'iat'  => $issuedAt,         // Issued at: time when the token was generated
                    'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
                    'iss'  => $serverName,       // Issuer
                    'nbf'  => $notBefore,        // Not before
                    'exp'  => $expire,           // Expire
                    'data' => [                  // Data related to the signer user
                        'userId'   => $user->id, // userid from the users table
                        'user' => $user->name, // User name
                        'event' => $event,
                    ],
                    'status' => "ok"
                ];

                $secret = getenv("JWT_SECRET");
                $jwt = JWT::encode($data, $secret);

                $_SESSION['event'] = $this->sr->getEventByLabel($_POST['event']);
                $_SESSION['user'] = $_POST['user'];
                $this->msg = null;
            }
            else {
                $_SESSION['event'] = null;
                $_SESSION['user'] = null;
                $jwt = null;
                $this->msg = 'Unrecognized password for ' . $_POST['user'];
            }

        }

        return $jwt;
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
