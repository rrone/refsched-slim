<?php
namespace Tests;

use App\Action\AbstractController;
use App\Action\AbstractView;
use App\Action\Refs\SchedRefsDBController;
use App\Action\Refs\SchedRefsView;

class SchedRefsTest extends AppTestCase
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

        $this->testUri = '/refs';

    }

    public function testSchedRefsAsAnonymous()
    {
        // instantiate the view and test it

        $view = new SchedRefsView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedRefsDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get($this->testUri);
        $url = implode($response->getHeader('Location'));

        $this->assertEquals('/', $url);
    }

    public function testSchedRefsAsUser()
    {
        // instantiate the view and test it

        $view = new SchedRefsView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedRefsDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->config['user_test']['user'];
        $projectKey = $this->config['user_test']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get($this->testUri);
        $view = (string)$response->getBody();

        $this->assertContains("<input class=\"btn btn-primary btn-xs \" type=\"submit\"",$view);
        $this->assertContains("value=\"Edit Assignments\"",$view);
    }

    public function testSchedRefsAsAdmin()
    {
        // instantiate the view and test it

        $view = new SchedRefsView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedRefsDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->config['admin_test']['user'];
        $projectKey = $this->config['admin_test']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get($this->testUri);
        $view = (string)$response->getBody();

        $this->assertContains("<input class=\"btn btn-primary btn-xs \" type=\"submit\"",$view);
        $this->assertContains("value=\"Edit Assignments\"",$view);
    }
}