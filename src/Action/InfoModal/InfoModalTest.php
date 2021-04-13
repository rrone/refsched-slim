<?php

namespace Tests;

use App\Action\AbstractController;
use App\Action\AbstractView;
use App\Action\InfoModal\InfoModalController;
use App\Action\InfoModal\InfoModalView;


class InfoModalTest extends AppTestCase
{
    protected $eventLabel;
    protected $userName;
    protected $passwd;

    /**
     *
     */
    public function setUp()
    {
//     Setup App controller
        $this->app = $this->getSlimInstance();
        $this->client = new AppWebTestClient($this->app);
    }

    public function testRoot()
    {
        // instantiate the view and test it

        $view = new InfoModalView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new InfoModalController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

    }

    /**
     *
     */
    public function testInfoModalAsUser()
    {
        $this->eventLabel = $this->config['testParams']['event'];
        $this->userName = $this->config['user_test']['user'];
        $this->passwd = $this->config['user_test']['passwd'];

        $url = '/';
        $headers = array(
            'cache-control' => 'no-cache',
            'content-type' => 'multipart/form-data;',
        );
        $body = array(
            'event' => $this->eventLabel,
            'user' => $this->userName,
            'passwd' => $this->passwd,
            'Submit' => 'InfoModal',
        );

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->post($url, $body, $headers);

        $url = implode($response->getHeader('Location'));
        $this->assertEquals('/greet', $url);

        $response = (object)$this->client->get($url);
        $view = (string)$response->getBody();
        $this->assertContains("<h3 class=\"center\">Welcome $this->userName Assignor</h3>", $view);
    }

//    /**
//     *
//     */
//    public function testInfoModalAsUserWithBadPW()
//    {
//        $this->eventLabel = $this->config['testParams']['event'];
//        $this->userName = $this->config['user_test']['user'];
//
//        $url = '/';
//        $headers = array(
//            'cache-control' => 'no-cache',
//            'content-type' => 'multipart/form-data;',
//        );
//        $body = array(
//            'event' => $this->eventLabel,
//            'user' => $this->userName,
//            'passwd' => '',
//            'Submit' => 'InfoModal',
//        );
//
//        $this->client->returnAsResponseObject(true);
//        $response = (object)$this->client->post($url, $body, $headers);
//        $view = (string)$response->getBody();
//        $h = $this->c['view'];
//        var_dump($this->c['view']);die();
//        $header = "<h1>$h</h1>";
//
//        $this->assertContains($header, $view);
//        $this->assertContains("Unrecognized password for $this->userName", $view);
//    }


    /**
     *
     */
    public function testInfoModalAsAdmin()
    {
        $this->eventLabel = $this->config['testParams']['event'];
        $this->userName = $this->config['admin_test']['user'];
        $this->passwd = $this->config['admin_test']['passwd'];

        $url = '/';
        $headers = array(
            'cache-control' => 'no-cache',
            'content-type' => 'multipart/form-data;',
        );
        $body = array(
            'event' => $this->eventLabel,
            'user' => $this->userName,
            'passwd' => $this->passwd,
            'Submit' => 'InfoModal',
        );

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->post($url, $body, $headers);

        $url = implode($response->getHeader('Location'));
        $this->assertEquals('/greet', $url);

        $response = (object)$this->client->get($url);
        $view = (string)$response->getBody();
        $this->assertContains("<h3 class=\"center\">Welcome $this->userName</h3>", $view);

    }

    /**
     *
     */
    public function testInfoModalAsDeveloper()
    {
        $this->eventLabel = $this->config['testParams']['event'];
        $this->userName = $this->config['dev_test']['user'];
        $this->passwd = $this->config['dev_test']['passwd'];

        $url = '/';
        $headers = array(
            'cache-control' => 'no-cache',
            'content-type' => 'multipart/form-data;',
        );
        $body = array(
            'event' => $this->eventLabel,
            'user' => $this->userName,
            'passwd' => $this->passwd,
            'Submit' => 'InfoModal',
        );

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->post($url, $body, $headers);

        $url = implode($response->getHeader('Location'));
        $this->assertEquals('/greet', $url);

        $response = (object)$this->client->get($url);
        $view = (string)$response->getBody();
        $this->assertContains("<h3 class=\"center\">Welcome $this->userName</h3>", $view);

    }

}