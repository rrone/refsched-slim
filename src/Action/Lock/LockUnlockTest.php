<?php
namespace Tests;

use App\Action\AbstractController;
use App\Action\AbstractView;
use App\Action\Lock\SchedLockDBController;
use App\Action\Lock\SchedUnlockDBController;
use App\Action\Lock\SchedLockView;

class LockUnlockTest extends AppTestCase
{
    public function setUp()
    {
//     Setup App controller
        $this->app = $this->getSlimInstance();

        $this->client = new AppWebTestClient($this->app);

    }

    public function testLockAsAdmin()
    {
        // instantiate the view

        $view = new SchedLockView($this->c, $this->sr);

        // instantiate the Unlock controller & test it

        $controller = new SchedLockDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the unlock controller action as unauthorized and test it
        $this->app->getContainer()['session'] = [
            'authed' => false,
            'user' => null,
            'event' => null
        ];

        // invoke the lock controller action as admin and test it
        $user = $this->config['admin_test']['user'];
        $projectKey = $this->config['admin_test']['projectKey'];

        $this->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/lock');
        $url = implode($response->getHeader('Location'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/greet', $url);

        $response = (object)$this->client->get($url);
        $view = (string)$response->getBody();

        $this->assertContains("<h3 class=\"center\">Welcome $user</h3>", $view);
        $this->assertContains("<h3 class=\"center\">The schedule is:&nbsp;<span style=\"color:#CC0000\">Locked</span>&nbsp;-&nbsp;(<a href=/unlock>Unlock</a> the schedule now)", $view);

        $response = (object)$this->client->get('/lock');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/greet', $url);
    }

    public function testUnlockAsUser()
    {
        // instantiate the view

        $view = new SchedLockView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the Lock controller & test it

        $controller = new SchedUnlockDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the lock controller action as authorized user and test it
        $this->app->getContainer()['session'] = [
            'authed' => false,
            'user' => null,
            'event' => null
        ];

        // invoke the lock controller action as user and test it
        $user = $this->config['user_test']['user'];
        $projectKey = $this->config['user_test']['projectKey'];
        $this->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/unlock');
        $url = implode($response->getHeader('Location'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/greet', $url);

        $response = (object)$this->client->get($url);
        $view = (string)$response->getBody();

        $this->assertContains("<h3 class=\"center\">Welcome $user Assignor</h3>", $view);
        $this->assertContains("<h3 class=\"center\">The schedule is presently <span style=\"color:#CC0000\">locked</span>", $view);
    }

    public function testUnlockAsAdmin()
    {
        // instantiate the view

        $view = new SchedLockView($this->c, $this->sr);

        // instantiate the Unlock controller & test it

        $controller = new SchedUnlockDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the unlock controller action as authorized admin and test it
        $this->app->getContainer()['session'] = [
            'authed' => false,
            'user' => null,
            'event' => null
        ];

        // invoke the lock controller action as user and test it
        $user = $this->config['admin_test']['user'];
        $projectKey = $this->config['admin_test']['projectKey'];

        $this->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/unlock');
        $url = implode($response->getHeader('Location'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/greet', $url);

        $response = (object)$this->client->get($url);
        $view = (string)$response->getBody();

        $this->assertContains("<h3 class=\"center\">Welcome $user</h3>", $view);
        $this->assertContains("<h3 class=\"center\">The schedule is:&nbsp;<span style=\"color:#02C902\">Unlocked</span>&nbsp;-&nbsp;(<a href=/lock>Lock</a> the schedule now)", $view);

        $response = (object)$this->client->get('/unlock');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/greet', $url);
    }

    public function testLockAsUser()
    {
        // instantiate the view

        $view = new SchedLockView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the Lock controller & test it

        $controller = new SchedLockDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the lock controller action as authorized user and test it
        $user = $this->config['user_test']['user'];
        $projectKey = $this->config['user_test']['projectKey'];

        $this->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/unlock');
        $url = implode($response->getHeader('Location'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/greet', $url);

        $response = (object)$this->client->get($url);
        $view = (string)$response->getBody();

        $this->assertContains("<h3 class=\"center\">Welcome $user Assignor</h3>", $view);
        $this->assertContains("<h3 class=\"center\">The schedule is presently <span style=\"color:#02C902\">unlocked</span>", $view);
    }

    public function testLockAsAnonymous()
    {
        // instantiate the view

        $view = new SchedLockView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the Lock controller & test it

        $controller = new SchedLockDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the lock controller action as unauthorized and test it
        $this->app->getContainer()['session'] = [
            'authed' => false,
            'user' => null,
            'event' => null
        ];

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/lock');
        $url = implode($response->getHeader('Location'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/greet', $url);

        $response = (object)$this->client->get($url);
        $url = implode($response->getHeader('Location'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/', $url);
    }

    public function testUnlockAsAnonymous()
    {
        // instantiate the view

        $view = new SchedLockView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the Lock controller & test it

        $controller = new SchedLockDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the lock controller action as unauthorized and test it
        $this->app->getContainer()['session'] = [
            'authed' => false,
            'user' => null,
            'event' => null
        ];

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/unlock');
        $url = implode($response->getHeader('Location'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/greet', $url);

        $response = (object)$this->client->get($url);
        $url = implode($response->getHeader('Location'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/', $url);
    }
}