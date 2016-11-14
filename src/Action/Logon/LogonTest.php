<?php
namespace Tests;

use There4\Slim\Test\WebTestClient;
use App\Action\AbstractController;
use App\Action\AbstractView;
use App\Action\Logon\LogonDBController;
use App\Action\Logon\LogonView;

class LogonTest extends AppTestCase
{
    public function setUp()
    {
//     Setup App controller
        $this->app = $this->getSlimInstance();
        $this->app->getContainer()['session'] = [
            'authed' => false,
            'user' => null,
            'event' => null
        ];

        $this->client = new WebTestClient($this->app);

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
        $view = $this->client->get('/');

        $this->assertContains('<h1>Section 1 Event Schedule</h1>',$view);
    }

//    public function testUserLogon()
//    {
//        $user = $this->local['user_test']['user'];
//        $passwd = $this->local['user_test']['passwd'];
//        $event = $this->local['user_test']['event'];
//
//        $url = '/';
//        $headers = array(
//            'postman-token' => '1b3de564-12b7-b8a3-7546-17d65a3d4d4c',
//            'cache-control' => 'no-cache',
//            'content-type' => 'multipart/form-data;'
//        );
//        $body = array(
//            'event' => $event,
//            'user' => $user,
//            'passwd' => $passwd,
//            'Submit' => 'Logon'
//        );
//
//        $this->client->app->getContainer()['session'] = [
//            'authed' => false,
//            'user' => null,
//            'event' => null
//        ];
//
//        $view = $this->client->post($url, $body, $headers);
//
//        $this->assertContains('<h3 class="center">Welcome Area 1B Assignor</h3>',$view);
//
//    }

}