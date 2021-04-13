<?php
namespace Tests;

use App\Action\AbstractController;
use App\Action\AbstractView;
use App\Action\Master\SchedMasterController;
use App\Action\Master\SchedMasterView;


class SchedMasterTest extends AppTestCase
{
    protected $testUri;

    public function setUp()
    {
//     Setup App controller
        $this->app = $this->getSlimInstance();
        $this->app->getContainer()['session'] = [
            'authed' => false,
            'user' => null,
            'event' => null
        ];

        $this->client = new AppWebTestClient($this->app);

        $this->testUri = '/master';

    }

    /**
     *
     */
    public function testSchedMasterAsAnonymous()
    {
        // instantiate the view and test it

        $view = new SchedMasterView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedMasterController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get($this->testUri);
        $url = implode($response->getHeader('Location'));

        $this->assertEquals('/greet', $url);
    }

    /**
     *
     */
    public function testSchedMasterAsUser()
    {
        // instantiate the view and test it

        $view = new SchedMasterView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedMasterController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->config['user_test']['user'];
        $projectKey = $this->config['testParams']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get($this->testUri);
        $url = implode($response->getHeader('Location'));

        $this->assertEquals('/greet', $url);
    }

    /**
     *
     */
    public function testSchedMasterAsAdmin()
    {
        // instantiate the view and test it

        $view = new SchedMasterView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedMasterController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->config['admin_test']['user'];
        $projectKey = $this->config['testParams']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get($this->testUri);
        $view = (string)$response->getBody();

        $this->assertContains("<form name=\"master_sched\" method=\"post\" action=/master>", $view);
    }

    /**
     *
     */
    public function testSchedMasterPostAsAdmin()
    {
        // instantiate the view and test it

        $view = new SchedMasterView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedMasterController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->config['admin_test']['user'];
        $assignor = $this->config['user_test']['user'];
        $projectKey = $this->config['testParams']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];
        $this->client->returnAsResponseObject(true);

        $url = $this->testUri;
        $headers = array(
            'cache-control' => 'no-cache',
            'content-type' => 'multipart/form-data;'
        );
        // clear match 1912 (match 1 in 202016U19UPlayoffs)
        $body = array(
            'Submit' => 'Submit',
            1912 => ''
        );

        $response = (object)$this->client->post($url, $body, $headers);
        $view = (string)$response->getBody();

        $this->assertContains("<form name=\"master_sched\" method=\"post\" action=/master>", $view);

        // assign match 1912 to Area 1B
        $body = array(
            'Submit' => 'Submit',
            1912 => $assignor
        );

        $response = (object)$this->client->post($url, $body, $headers);
        $view = (string)$response->getBody();

        $this->assertContains("<form name=\"master_sched\" method=\"post\" action=/master>", $view);
    }
}