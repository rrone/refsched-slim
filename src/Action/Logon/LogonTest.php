<?php
namespace Tests;

use App\Action\AbstractController;
use App\Action\AbstractView;
use App\Action\Logon\LogonDBController;
use App\Action\Logon\LogonView;

class LogonTest extends AppTestCase
{
    protected $eventLabel;
    protected $userName;
    protected $passwd;

    public function setUp()
    {
//     Setup App controller
        $this->app = $this->getSlimInstance();
        $this->client = new AppWebTestClient($this->app);

        $this->eventLabel = $this->local['user_test']['event'];
        $this->userName = $this->local['user_test']['user'];
        $this->passwd = $this->local['user_test']['passwd'];
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
        $this->client->app->getContainer()['session'] = [
            'authed' => false,
            'user' => null,
            'event' => null
        ];

        $view = $this->client->get('/');

        $this->assertContains('<h1>Section 1 Event Schedule</h1>', $view);
    }

    public function testUserLogon()
    {
        $sr = (object)$this->sr;

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $sr->getUserByName($this->userName),
            'event' => $sr->getEventByLabel($this->eventLabel)
        ];

        $url = '/';
        $headers = array(
            'postman-token' => '1b3de564-12b7-b8a3-7546-17d65a3d4d4c',
            'cache-control' => 'no-cache',
            'content-type' => 'multipart/form-data;'
        );
        $body = array(
            'event' => $this->eventLabel,
            'user' => $this->userName,
            'passwd' => $this->passwd,
            'Submit' => 'Logon'
        );

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->post($url, $body, $headers);
        $url = implode($response->getHeader('Location'));
        $this->assertEquals('/greet', $url);

        $response = (object)$this->client->get($url);
        $view = (string)$response->getBody();
        $this->assertContains('<h3 class="center">Welcome Area 1B Assignor</h3>', $view);

    }

}