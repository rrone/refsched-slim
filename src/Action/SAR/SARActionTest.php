<?php
namespace Tests;

use App\Action\AbstractController;
use App\Action\SAR\SARAction;

class SARActionTest extends AppTestCase
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

        $this->testUri = '/sar/Region 92';

    }

    public function testSARAsAnonymous()
    {
        // instantiate the action

        $controller = new SARAction($this->sr);
        $this->assertFalse($controller instanceof AbstractController);

        // invoke the controller action and test it

        $this->client->returnAsResponseObject(true);
        $this->client->get($this->testUri);

        $this->expectOutputString('1/D/92');
    }

    public function testSSARAsUser()
    {
        // instantiate the action

        $controller = new SARAction($this->sr);
        $this->assertFalse($controller instanceof AbstractController);

        $user = $this->config['user_test']['user'];
        $projectKey = $this->config['user_test']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        // invoke the controller action and test it

        $this->client->returnAsResponseObject(true);
        $this->client->get($this->testUri);

        $this->expectOutputString('1/D/92');
    }

    public function testSARAsAdmin()
    {
        // instantiate the action

        $controller = new SARAction($this->sr);
        $this->assertFalse($controller instanceof AbstractController);

        $user = $this->config['admin_test']['user'];
        $projectKey = $this->config['admin_test']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        // invoke the controller action and test it

        $this->client->returnAsResponseObject(true);
        $this->client->get($this->testUri);

        $this->expectOutputString('1/D/92');
    }
}