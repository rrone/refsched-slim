<?php
namespace Tests;

use App\Action\LogonDBController;
use Slim\Http\Response;
use Slim\Http\Environment;

class Logon extends \PHPUnit_Framework_TestCase
{
    public function testGetRequestReturnsEcho()
    {
        // We need a request and response object to invoke the action
        $environment = Environment::mock([
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI' => '/echo',
                'QUERY_STRING'=>'foo=bar']
        );
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = new Response();
        // instantiate action
        $action = new \App\Action\LogonDBController($request, $res);


        // run the controller action and test it
        $response = $action($request, $response, []);
        $this->assertSame((string)$response->getBody(), '{"foo":"bar"}');
    }
}