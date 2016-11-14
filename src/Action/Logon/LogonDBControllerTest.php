<?php
namespace Tests;

use App\Action\AbstractController;
use App\Action\AbstractView;
use App\Action\Logon\LogonDBController;
use App\Action\Logon\LogonView;
use There4\Slim\Test\WebTestClient;

class LogonDBControllerTest extends AppTestCase
{
    private $user;
    private $passwd;
    private $event;
    private $projectKey;

    public function setUp()
    {
//     Setup App controller
        $this->app = $this->getSlimInstance();
        $this->client = new WebTestClient($this->app);

        $this->user = $this->local['admin_test']['user'];
        $this->passwd = $this->local['admin_test']['passwd'];
        $this->event = $this->local['admin_test']['event'];
        $this->projectKey = $this->local['admin_test']['projectKey'];

        $this->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($this->user),
            'event' => $this->sr->getEvent($this->projectKey)
        ];

    }

    public function testRoot()
    {
        // instantiate the view and test it

        $view = new LogonView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new LogonDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it
        $view = $this->client->post('/');

        $this->assertContains('<h1>Section 1 Event Schedule</h1>',$view);
    }

    public function UserLogon()
    {
        // instantiate the view and test it

        $view = new LogonView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new LogonDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $this->user = $this->local['admin_test']['user'];
        $this->passwd = $this->local['admin_test']['passwd'];
        $this->event = $this->local['admin_test']['event'];
        $this->projectKey = $this->local['admin_test']['projectKey'];

        $this->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($this->user),
            'event' => $this->sr->getEvent($this->projectKey)
        ];

        $view = $this->client->post('/greet');
        $this->assertContains('<h3 class="center">Welcome Area 1B Assignor</h3>',$view);
    }

    public function AdminLogon()
    {
        // instantiate the view and test it

        $view = new LogonView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new LogonDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it
        $this->user = $this->local['admin_test']['user'];
        $this->passwd = $this->local['admin_test']['passwd'];
        $this->event = $this->local['admin_test']['event'];
        $this->projectKey = $this->local['admin_test']['projectKey'];

        $this->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($this->user),
            'event' => $this->sr->getEvent($this->projectKey)
        ];

        $view = $this->client->post('/greet');
        $this->assertContains('<h3 class="center">Welcome Area 1B Assignor</h3>',$view);
    }

    public function testAdminLogon()
    {
        $url = 'http://refsched.slim.vhx.host/';
        $method = 'HTTP_METH_POST';
        $headers = array(
            'postman-token' => '17a59f32-85e2-857f-ddda-6011b27014f2',
            'cache-control' => 'no-cache',
            'content-type' => 'multipart/form-data; boundary=---011000010111000001101001'
        );
        $cookies = null;
        $serverParams = null;
        $bodyData = "-----011000010111000001101001
            Content-Disposition: form-data; name='event'
            
            $event
            -----011000010111000001101001
            Content-Disposition: form-data; name='user'
            
            $user
            -----011000010111000001101001
            Content-Disposition: form-data; name='passwd'
            
            $passwd
            -----011000010111000001101001
            Content-Disposition: form-data; name='Submit'
            
            Logon
            -----011000010111000001101001--";
        $uploadedFiles = null;

        $request = new $this->requestFactory($method, $url, $headers, $cookies, $serverParams, $bodyData, $uploadedFiles);

        $response = $request->send();

        echo $response->getBody();
    }
}