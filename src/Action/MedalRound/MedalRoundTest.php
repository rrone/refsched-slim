<?php
namespace Tests;

use App\Action\AbstractController;
use App\Action\AbstractView;
use App\Action\MedalRound\HideMedalRoundController;
use App\Action\MedalRound\ShowMedalRoundController;
use App\Action\MedalRound\MedalRoundView;

class MedalRoundTest extends AppTestCase
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

        $view = new MedalRoundView($this->c, $this->sr);

        // instantiate the ShowMedalRound controller & test it

        $controller = new HideMedalRoundController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the show medalround controller action as unauthorized and test it
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
//        $this->assertContains("<h3 class=\"center\">The schedule is:&nbsp;<span style=\"color:#CC0000\">Locked</span>&nbsp;-&nbsp;(<a href=/unlock>Unlock</a> the schedule now)", $view);

        $response = (object)$this->client->get('/hidemr');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/greet', $url);
    }

    public function testShowMRAsUser()
    {
        // instantiate the view

        $view = new MedalRoundView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the Lock controller & test it

        $controller = new ShowMedalRoundController($this->c, $view);
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
        $response = (object)$this->client->get('/showmr');
        $url = implode($response->getHeader('Location'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/greet', $url);

        $response = (object)$this->client->get($url);
        $view = (string)$response->getBody();

        $this->assertContains("<h3 class=\"center\">Welcome $user Assignor</h3>", $view);
    }

    public function testShowMRAsAdmin()
    {
        // instantiate the view

        $view = new MedalRoundView($this->c, $this->sr);

        // instantiate the Unlock controller & test it

        $controller = new ShowMedalRoundController($this->c, $view);
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

        $response = (object)$this->client->get('/unlock');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/greet', $url);
    }

    public function testHideMRAsUser()
    {
        // instantiate the view

        $view = new MedalRoundView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the HideMedalRoundController & test it

        $controller = new HideMedalRoundController($this->c, $view);
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
    }

    public function testHideAsAnonymous()
    {
        // instantiate the view

        $view = new MedalRoundView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the HideMedalRound controller & test it

        $controller = new HideMedalRoundController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action as unauthorized and test it
        $this->app->getContainer()['session'] = [
            'authed' => false,
            'user' => null,
            'event' => null
        ];

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/hidemr');
        $url = implode($response->getHeader('Location'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/greet', $url);

        $response = (object)$this->client->get($url);
        $url = implode($response->getHeader('Location'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/', $url);
    }

    public function testShowAsAnonymous()
    {
        // instantiate the view

        $view = new MedalRoundView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller & test it

        $controller = new ShowMedalRoundController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the lock controller action as unauthorized and test it
        $this->app->getContainer()['session'] = [
            'authed' => false,
            'user' => null,
            'event' => null
        ];

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/showmr');
        $url = implode($response->getHeader('Location'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/greet', $url);

        $response = (object)$this->client->get($url);
        $url = implode($response->getHeader('Location'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/', $url);
    }
}