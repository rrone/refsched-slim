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
		$this->events = $repository->getEvents();
		$this->enabled = $repository->getEnabled();
		
    }
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->logger->info("Logon database page action dispatched");
        
		$content = array(
			'events' => $this->getEventList(),
            'content' => $this->renderLogon()
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
            <td width="50%"><select name="area">
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
                <select name="event">
EOD;
		foreach($enabled as $option) {
			$html .= "<option>$option->eventKey</option>";
		}
		
		$html .=
<<<EOD
                </select>
            </td>
          </tr>
		  </table>
          <p>
            <input type="submit" name="Submit" value="Logon">      
          </p>
        </div>
      </form>
EOD;

        return $html;
    }
	private function getEventList()
	{
		$eventList = [];
		
		foreach($this->events as $event){
			$eventList[] = array(
				'name' => $event->name,
				'location' => $event->location,
				'dates' => $event->dates
			);
		}
		
		return $eventList;
	}
}
