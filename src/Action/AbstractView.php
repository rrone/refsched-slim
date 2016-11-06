<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 11/6/16
 * Time: 8:23 AM
 */

namespace App\Action;

use Slim\Views\Twig;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AbstractView
{
    /* @var Twig */
    protected $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }
    protected function handler(Request $request, Response $response)
    {

    }
    protected function render(Response &$response)
    {

    }
}