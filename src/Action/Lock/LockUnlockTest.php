<?php
namespace Tests;

use There4\Slim\Test\WebTestClient;
use App\Action\AbstractController;
use App\Action\AbstractView;
use Slim\Http\Response;
use App\Action\Lock\SchedLockDBController;
use App\Action\Lock\SchedUnlockDBController;
use App\Action\Lock\SchedLockView;

class LockUnlockTest extends AppTestCase
{
    public function setUp()
    {
//     Setup App controller
        $this->app = $this->getSlimInstance();
    }

    public function testView()
    {
        // instantiate the view and test it

        $this->view = new SchedLockView($this->c, $this->sr);
        $this->assertTrue($this->view instanceof AbstractView);

    }

    public function testLock()
    {
        // instantiate the view

        $view = new SchedLockView($this->c, $this->sr);

        // instantiate the Lock controller & test it

        $controller = new SchedLockDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the lock controller action as unauthorized and test it
        $this->app->getContainer()['session'] = [
            'authed' => false,
            'user' => null,
            'event' => null
        ];

        $this->client = new WebTestClient($this->app);

        $request  = $this->request('GET', '/lock');
        $response = new Response();

        $app = $this->client->app;
        $response = $app($request, $response);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('Unauthorized: back to logon',(string)$response->getBody());

        // invoke the lock controller action as user and test it
        $user = $this->local['user_test']['user'];
        $projectKey = $this->local['user_test']['projectKey'];
        $this->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $this->client = new WebTestClient($this->app);

        $request  = $this->request('GET', '/lock');
        $response = new Response();

        $app = $this->client->app;
        $response = $app($request, $response);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('Unauthorized: back to logon',(string)$response->getBody());

        // invoke the lock controller action as unauthorized and test it
        $user = $this->local['admin_test']['user'];
        $projectKey = $this->local['admin_test']['projectKey'];

        $this->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $this->client = new WebTestClient($this->app);

        $request  = $this->request('GET', '/lock');
        $response = new Response();

        $app = $this->client->app;
        $response = $app($request, $response);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('Locked: back to greet',(string)$response->getBody());

    }

    public function testUnlock()
    {
        // instantiate the view

        $view = new SchedLockView($this->c, $this->sr);

        // instantiate the Unlock controller & test it

        $controller = new SchedUnlockDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the unlock controller action as unauthorized and test it
        $this->app->getContainer()['session'] = [
            'authed' => false,
            'user' => null,
            'event' => null
        ];

        $this->client = new WebTestClient($this->app);

        $request  = $this->request('GET', '/unlock');
        $response = new Response();

        $app = $this->client->app;
        $response = $app($request, $response);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('Unauthorized: back to logon',(string)$response->getBody());

        // invoke the lock controller action as user and test it
        $user = $this->local['user_test']['user'];
        $projectKey = $this->local['user_test']['projectKey'];
        $this->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $this->client = new WebTestClient($this->app);

        $request  = $this->request('GET', '/unlock');
        $response = new Response();

        $app = $this->client->app;
        $response = $app($request, $response);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('Unauthorized: back to logon',(string)$response->getBody());

        // invoke the lock controller action as admin and test it
        $user = $this->local['admin_test']['user'];
        $projectKey = $this->local['admin_test']['projectKey'];

        $this->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $this->client = new WebTestClient($this->app);

        $request  = $this->request('GET', '/unlock');
        $response = new Response();

        $app = $this->client->app;
        $response = $app($request, $response);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('Unlocked: back to greet',(string)$response->getBody());

    }
}