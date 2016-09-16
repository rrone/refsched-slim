<?php

namespace App\Action;

use Slim\App;
use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

abstract class AbstractController
{
    protected $view;
    protected $logger;
    protected $container;
    protected $root;
    protected $refdata;
    protected $authdat;
    protected $rep;
    protected $page_title;
    protected $authed;
    
    protected $colorTitle = '#80ccff';
    protected $colorOpen = '#00FFFF';
    protected $colorGroup = '#00FF88';
    protected $colorNotGroup = '#ffcccc';
    protected $colorHighlight = '#FFBC00';
    protected $colorAlert = '#CC0000';
    protected $colorWarning = '#CC00CC';
    protected $colorSuccess = '#008800';
    
    protected $addrefPath;
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
        
        $this->root = $_SERVER['DOCUMENT_ROOT'] . '/..';
        $this->refdata = $this->root . '/var/refdata/';
        $this->authdat = $this->root . '/var/dat/';

        $this->addrefPath = $this->container->get('router')->pathFor('addref');
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
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->view->render($response, 'base.html.twig');
        return $response;
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
}
