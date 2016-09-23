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
	private $sr;
	
	public function __construct(Container $container, SchedulerRepository $repository) {
		
		parent::__construct($container);
		
		$this->sr = $repository;
		
		$this->users = $repository->getUsers();
		$this->events = $repository->getCurrentEvents();
		$this->enabled = $repository->getEnabledEvents();
		
    }
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Logon database page action dispatched");
       
		$content = array(
			'events' => $this->sr->getCurrentEvents(),
            'content' => $this->renderLogon(),
			'message' => isset($_SESSION['msg']) ? $_SESSION['msg'] : null,
        );
      
        $this->view->render($response, 'logon.html.twig', $content);      

        return $response;
    }
    private function renderLogon()
    {
		$users = $this->users;
		$enabled = $this->enabled;
		
        $html =
<<<EOD
      <form name="form1" method="post" action="$this->greetPath">
        <div align="center">
		  <table>
          <tr><td width="50%"><div align="right">ARA or representative from: </div></td>
            <td width="50%"><select class="left-margin" name="area">
EOD;
		foreach($users as $user) {
			$html .= "<option>$user->name</option>";
		}
		
		$html .=
<<<EOD
            </select></td>
          </tr>
          <tr><td width="50%"><div align="right">Password: </div></td>
            <td><input type="password" name="passwd"></td></tr>
            <tr><td width="50%"><div align="right">Competition: </div></td>
            <td width="50%">
                <select class="left-margin" name="event">
EOD;
		foreach($enabled as $option) {
			$html .= "<option>$option->label</option>";
		}
		
		$html .=
<<<EOD
                </select>
            </td>
          </tr>
		  </table>
          <p>
            <input type="submit" type="button" class="btn btn-primary btn-xs active" name="Submit" value="Logon">      
          </p>
        </div>
      </form>
EOD;

        return $html;
    }
}
