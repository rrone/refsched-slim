<?php

namespace App\Action;

use Slim\Container;
use Slim\Http\Request;

abstract class AbstractController
{
    //database connection
    protected $conn;

    /* @var Container */
    protected $container;
	
    //shared variables
    protected $root;

	//session variables
	protected $event;
    protected $user;
    protected $authed;

    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->root = __DIR__ . '/../../var';
    }
    private function isTest()
    {
        return $this->container->get('settings.test');
    }
    protected function isAuthorized()
    {
        if($this->isTest()){
            $session = $this->container['session'];
            $_SESSION['authed'] = $session['authed'];
            $_SESSION['user'] = $session['user'];
            $_SESSION['event'] = $session['event'];
        }

        $this->authed = isset($_SESSION['authed']) ? $_SESSION['authed'] : null;
        if (!$this->authed) {
            return null;
        }

        $this->event = isset($_SESSION['event']) ? $_SESSION['event'] : null;
        $this->user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

        if (is_null($this->event) || is_null($this->user)) {
            return null;
        }

        return true;
    }
    protected function logStamp(Request $request)
    {
        if($this->isTest()){
            return null;
        }

        if(isset($_SESSION['admin'])){
            return null;
        }

        $sr = $this->container['sr'];

        if(is_null($sr)){
            return null;
        }

        $uri = $request->getUri()->getPath();
        $user = isset($this->user) ? $this->user->name : 'Anonymous';
        $projectKey = isset($this->event) ? $this->event->projectKey : '';
        $post = $request->isPost() ? 'with updated ref assignments' : '';

        switch ($uri) {
            case $this->container->get('logonPath'):
            case '/':
            case 'logon':
            case '/logon':
                //TODO: Why is $uri == '/adm' passing this case?
                $logMsg = $uri != $this->container->get('adminPath') ? "$user: Scheduler logon" : null;
                break;
            case $this->container->get('endPath'):
            case 'end':
                $logMsg = "$user: Scheduler log off";
                break;
            case $this->container->get('editrefPath'):
            case 'editref':
                if(!empty($post)) {
                    $logMsg = "$user: Scheduler $uri dispatched $post";
                } else {
                    return null;
                }
                break;
            case $this->container->get('fullPath'):
            case 'full':
                $msg = isset($_GET['open']) ? ' no referees view' : '';
                $logMsg = "$user: Scheduler $uri$msg dispatched";
                break;
            case $this->container->get('schedPath'):
            case 'sched':
                $showgroup = isset($_GET[ 'group' ]) ? $_GET[ 'group' ] : null;
                $msg = empty($showgroup) ? '' : " for $showgroup";
                $logMsg = "$user: Scheduler $uri$msg dispatched";
                break;
            default:
                $logMsg = "$user: Scheduler $uri dispatched";
                break;
        }

        if(!is_null($logMsg)){
            $sr->logInfo($projectKey, $logMsg);
        }

        return null;

    }
}
