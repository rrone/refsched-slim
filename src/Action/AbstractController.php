<?php

namespace App\Action;

use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class AbstractController
{
    //database connection
    protected $conn;

    //schedule repository
    protected $sr;
	
    //shared variables
    protected $view;
    protected $logger;
    protected $container;
    protected $root;

	//session variables
	protected $event;
    protected $user;
    protected $authed;

    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->view = $container->get('view');

        $this->root = __DIR__ . '/../../var';
    }
    protected function logStamp($request)
    {
        if(isset($_SESSION['admin'])){
            return null;
        }

        $uri = $request->getURI()->getPath();
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
            $this->sr->logInfo($projectKey, $logMsg);
        }

        return null;

    }
}
