<?php
namespace App\Action\Handshake;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Action\SchedulerRepository;
use App\Action\AbstractController;

class HandshakeController extends AbstractController
{
    private $users;
    private $enabled;

    public function __construct(Container $container, SchedulerRepository $repository) {

        parent::__construct($container);

        $this->sr = $repository;

        $this->users = $repository->getUsers();
        $this->enabled = $repository->getEnabledEvents();
    }
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Handshake page action dispatched");

        $this->handleRequest($request);

        if (!$this->authed) {
            $content = array(
                'content' => $this->renderLogon(),
                'message' => $this->msg,
                'script' => null//$this->getScript()
            );

            $this->view->render($response, 'handshake.html.twig', $content);

            return $response;
        }
        else {

            return $response->withRedirect($this->greetPath);
        }
    }
    public function getAuth($request)
    {
        $this->authed = null;
        $this->logger->info("Handshake page: getAuth"); ////

        if ($request->isPut()) {
            $this->logger->info("Handshake page: PUT"); ////

            $json = $request->getParsedBody();
            $data = json_decode($json['ValArray']);

            $user = $data->user;
            $event = $data->event;
            $passwd = $data->passwd;

            $logMsg = "Handshake page: Data received: " . $json;
            $this->logger->info($logMsg);

            $event = !empty($event) ? $this->sr->getEventByLabel($event) : null;
            $userName = !empty($user) ? $user : null;
            $user = $this->sr->getUserByName($userName);
            $pass = !empty($passwd) ? $passwd : null;

            $hash = isset($user) ? $user->hash : null;

            $this->authed = password_verify($pass, $hash);

            $logMsg = "Handshake page: Authorized: $this->authed";
            $this->logger->info($logMsg);

            if ($this->authed) {
                $data = array(
                    'event' => $event,
                    'user' => $user
                );

                $jwt = $this->tm->jwt($data);

                $logMsg = "Handshake page: JWT: " . $jwt; ////
                $this->logger->info($logMsg);

                echo $jwt;

            } else {

                echo ('HTTP/1.0 401 Unauthorized');
            }
        }
    }
    private function handleRequest($request)
    {
        if ($request->isPost()){
            $this->logger->info("Handshake page: POST");////

            $this->authed = !is_null($this->getData($request)); //load the event, user, target_id

            $logMsg = (string)"Handshake page: Authorized: " . $this->authed;////
            $this->logger->info($logMsg);
        } else {
            $this->logger->info("Handshake page: GET"); ////
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
                      <form id="login_form" name="login_form" method="post" action="$this->logonPath">
        <div align="center">
			<table>
				<tr><td width="50%"><div align="right">Event: </div></td>
					<td width="50%">
						<select class="form-control left-margin" name="event" id="event">
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
					<td width="50%"><select class="form-control left-margin" name="user" id="user">
EOD;
            foreach ($users as $user) {
                $html .= "<option>$user->name</option>";
            }

            $html .= <<<EOD
                			            </select></td>
				</tr>

				<tr>
					<td width="50%"><div align="right">Password: </div></td>
					<td><input class="form-control" type="password" name="passwd" id="passwd"></td>
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
    protected function getScript()
    {
        $js = <<<JSO
        
$("#frmLogon").click( function(){

     var formData = JSON.stringify({
         "user" : document.getElementById('user').value,
         "passwd" : document.getElementById('passwd').value,
         "event" : document.getElementById('event').value
     });
     
     sessionStorage.sessionKeys = formData;
     console.log(formData);

     $.ajax(
     {
         url : "/logon/auth/",
         type: "PUT",
         beforeSend: function (xhr){ 
            xhr.setRequestHeader('ValArray', formData); 
         }
         success:function(maindta)
         {
             alert('Success');
         },
         error: function(jqXHR, textStatus, errorThrown)
         {
            alert('Failure');
         }
     })
     .done(function(data) {
        $.ajax(
        {
             url : "/shake",
             type: "GET",
             beforeSend: function (xhr){ 
                xhr.setRequestHeader('SessionKeys', sessionStorage.getItem('sessionKeys')); 
             }
        });         
     });         
});
JSO;
        return $js;
    }
}
