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
        if ($this->isTest() && isset($this->container['session'])) {
            unset ($_SESSION);
            $session = $this->container['session'];
            $_SESSION['authed'] = $session['authed'];
            $_SESSION['user'] = $session['user'];
            $_SESSION['event'] = $session['event'];
            if (isset($session['game_id'])) {
                $_SESSION['game_id'] = $session['game_id'];
            }
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

        $this->getParams($this->event);

        return true;
    }

    protected function getParams($event)
    {
        if (!is_null($event)) {
            $sr = $this->container['sr'];

            $enabledEvents = array_values((array)$sr->getEnabledEvents())[0];

            if (in_array($this->event, $enabledEvents)) {
                $_SESSION['param'] = $this->event->projectKey;;
            }
        }
    }

    protected function logStamp(Request $request)
    {
//        if($this->isTest()){
//            return null;
//        }
//
        if (isset($_SESSION['admin'])) {
            return null;
        }

        $sr = $this->container['sr'];

        if (is_null($sr)) {
            return null;
        }

        $_GET = $request->getParams();
        $uri = $request->getUri()->getPath();
        $uriPath = substr($uri, 1);
        $user = isset($this->user) ? $this->user->name : 'Anonymous';
        $projectKey = isset($this->event) ? $this->event->projectKey : '';
        $post = $request->isPost() ? 'with updated ref assignments' : '';

        switch ($uri) {
            case $this->getBaseURL('logonPath'):
            case '/':
            case '/logon':
                //TODO: Why is $uri == '/adm' passing this case?
                $logMsg = $uri != $this->getBaseURL('adminPath') ? "$user: Scheduler logon" : null;
                break;
            case $this->getBaseURL('endPath'):
            case '/end':
                $logMsg = "$user: Scheduler log off";
                break;
            case $this->getBaseURL('editrefPath'):
            case '/editref':
                if (!empty($post)) {
                    $logMsg = "$user: Scheduler $uriPath dispatched $post";
                } else {
                    return null;
                }
                break;
            case $this->getBaseURL('fullPath'):
            case '/full':
                $msg = isset($_GET['open']) ? ' no referees view' : '';
                $logMsg = "$user: Scheduler $uriPath$msg dispatched";
                break;
            case $this->getBaseURL('schedPath'):
            case '/sched':
                $showgroup = isset($_GET['group']) ? $_GET['group'] : null;
                $msg = empty($showgroup) ? '' : " for $showgroup";
                $logMsg = "$user: Scheduler $uriPath$msg dispatched";
                break;
            default:
                $logMsg = "$user: Scheduler $uriPath dispatched";
                break;
        }

        if (!is_null($logMsg)) {
            $sr->logInfo($projectKey, $logMsg);
        }

        return null;

    }

    protected function getBaseURL($path)
    {
        $request = $this->container->get('request');

        $baseUri = $request->getUri()->getBasePath() . $this->container->get($path);

        return $baseUri;
    }

}
