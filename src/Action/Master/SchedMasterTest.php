<?php
namespace Tests;

use App\Action\AbstractController;
use App\Action\AbstractView;
use App\Action\Master\SchedMasterDBController;
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

    public function testEditGameAsUser()
    {
        // instantiate the view and test it

        $view = new SchedMasterView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedMasterDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->local['user_test']['user'];
        $projectKey = $this->local['user_test']['projectKey'];

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

    public function testEditGameAsAdmin()
    {
        // instantiate the view and test it

        $view = new SchedMasterView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedMasterDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->local['admin_test']['user'];
        $projectKey = $this->local['user_test']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get($this->testUri);
        $view = (string)$response->getBody();

        $this->assertContains("<input class=\"btn btn-primary btn-xs right\" type=\"submit\" name=\"Submit\" value=\"Submit\">",$view);
    }

}