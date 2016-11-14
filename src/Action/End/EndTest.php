<?php
namespace Tests;

use App\Action\End\SchedEndController;
use There4\Slim\Test\WebTestClient;
use App\Action\AbstractController;
use Slim\Http\Response;

class EndTest extends AppTestCase
{
    public function setUp()
    {
        $this->app = $this->getSlimInstance();

        $this->client = new WebTestClient($this->app);
    }

    public function testEnd()
    {
        // instantiate the controller & test it
        $controller = new SchedEndController($this->c);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $request  = $this->request('GET', '/end');
        $response = new Response();

        $app = $this->client->app;
        $response = $app($request, $response);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('back to logon',(string)$response->getBody());
    }

}