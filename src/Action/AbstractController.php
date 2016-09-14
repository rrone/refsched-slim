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

    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->view = $container->get('view');
        $this->logger = $container->get('logger');
        
        $this->root = $_SERVER['DOCUMENT_ROOT'] . '/..';
        $this->refdata = $this->root . '/var/refdata/';
        $this->authdat = $this->root . '/var/dat/';

    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->view->render($response, 'base.html.twig');
        return $response;
    }
}
