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

    public function testSchedRefsAsAnonymous()
    {
        // instantiate the action

        $controller = new SARAction($this->c, $this->sr);
        $this->assertFalse($controller instanceof AbstractController);

        // invoke the controller action and test it

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get($this->testUri);
        $sar = (string)$response->getBody();

        $this->assertContains('1/D/92', $sar);
    }

    public function testSchedRefsAsUser()
    {
        // instantiate the action

        $controller = new SARAction($this->c, $this->sr);
        $this->assertFalse($controller instanceof AbstractController);

        $user = $this->local['user_test']['user'];
        $projectKey = $this->local['user_test']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        // invoke the controller action and test it

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get($this->testUri);
        $sar = (string)$response->getBody();

        $this->assertContains('1/D/92', $sar);
    }

    public function testSchedRefsAsAdmin()
    {
        // instantiate the action

        $controller = new SARAction($this->c, $this->sr);
        $this->assertFalse($controller instanceof AbstractController);

        $user = $this->local['admin_test']['user'];
        $projectKey = $this->local['admin_test']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        // invoke the controller action and test it

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get($this->testUri);
        $sar = (string)$response->getBody();

        $this->assertContains('1/D/92', $sar);
    }
}