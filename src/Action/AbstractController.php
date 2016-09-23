<?php

namespace App\Action;

use Slim\App;
use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

abstract class AbstractController
{
    //database connection
    protected $conn;
	
    //shared variables
    protected $view;
    protected $logger;
    protected $container;
    protected $root;

	//view variables
    protected $page_title;
	protected $dates;
	protected $location;
	protected $msg;

	//session variables	
	protected $event;
    protected $rep;
    protected $authed;
    
    //default layout colors
    protected $colorTitle = '#80ccff';
    protected $colorOpen = '#00FFFF';
    protected $colorGroup = '#00FF88';
    protected $colorNotGroup = '#ffcccc';
    protected $colorHighlight = '#FFBC00';
    protected $colorAlert = '#CC0000';
    protected $colorWarning = '#CC00CC';
    protected $colorSuccess = '#008800';
    
    //named routes
    protected $assignPath;
    protected $controlPath;
    protected $editrefPath;
    protected $endPath;
    protected $fullPath;
    protected $greetPath;
    protected $lockPath;
    protected $logonPath;
    protected $masterPath;
    protected $refsPath;
    protected $schedPath;
    protected $unlockPath;

    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->view = $container->get('view');
        $this->logger = $container->get('logger');
        $this->root = __DIR__ . '/../../var';

        $this->refdata = $this->root . '/refdata/';
        $this->authdat = $this->root . '/dat/';
        
        $this->page_title = "Section 1 Referee Scheduler";

        $this->assignPath = $this->container->get('router')->pathFor('assign');
        $this->controlPath = $this->container->get('router')->pathFor('control');
        $this->editrefPath = $this->container->get('router')->pathFor('editref');
        $this->endPath = $this->container->get('router')->pathFor('end');
        $this->fullPath = $this->container->get('router')->pathFor('full');
        $this->greetPath = $this->container->get('router')->pathFor('greet');
        $this->lockPath = $this->container->get('router')->pathFor('lock');
        $this->logonPath = $this->container->get('router')->pathFor('logon');
        $this->masterPath = $this->container->get('router')->pathFor('master');
        $this->refsPath = $this->container->get('router')->pathFor('refs');
        $this->schedPath = $this->container->get('router')->pathFor('sched');
        $this->unlockPath = $this->container->get('router')->pathFor('unlock');
        
    }
    protected function errorCheck()
    {
        $html = null;
        
        if ( !$this->authed ) {
            $html .= "<center><h2>You need to <a href=\"$this->logonPath\">logon</a> first.</h2></center>";
        }
        else {
            $html .= "<center><h1>Something is not right</h1></center>";
        }
        
        return $html;
    }
	protected function divisionAge($div)
	{
		return substr($div,0,3);
	}
}
