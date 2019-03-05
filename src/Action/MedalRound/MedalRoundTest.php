<?php
namespace Tests;

use App\Action\AbstractController;
use App\Action\AbstractView;
use App\Action\MedalRound\HideMedalRoundController;
use App\Action\MedalRound\MedalRoundDivisionsView;
use App\Action\MedalRound\ShowMedalRoundController;
use App\Action\MedalRound\MedalRoundView;
use App\Action\MedalRound\ShowMedalRoundDivisionsController;

class MedalRoundTest extends AppTestCase
{
    /**
     *
     */
    public function setUp()
    {
//     Setup App controller
        $this->app = $this->getSlimInstance();

        $this->client = new AppWebTestClient($this->app);

    }

    /**
     * @throws \Interop\Container\Exception\ContainerException
     */
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

        $response = (object)$this->client->get('/unlock');
        $url = implode($response->getHeader('Location'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/greet', $url);

        $response = (object)$this->client->get($url);
        $view = (string)$response->getBody();

        $this->assertContains("<h3 class=\"center\">Welcome $user</h3>", $view);
        $this->assertContains("<h3 class=\"center\">The schedule is:&nbsp;<span style=\"color:#02C902\">Unlocked</span>&nbsp;-&nbsp;(<a href=/lock>Lock</a> the schedule now)", $view);

        $response = (object)$this->client->get('/lock');
        $url = implode($response->getHeader('Location'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/greet', $url);

        $response = (object)$this->client->get($url);
        $view = (string)$response->getBody();

        $this->assertContains("<h3 class=\"center\">Welcome $user</h3>", $view);
        $this->assertContains("<h3 class=\"center\">The schedule is:&nbsp;<span style=\"color:#CC0000\">Locked</span>&nbsp;-&nbsp;(<a href=/unlock>Unlock</a> the schedule now)", $view);

    }

    /**
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function testLockAsUser()
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
        $this->assertContains("<h3 class=\"center\">The schedule is presently <span style=\"color:#CC0000\">locked</span><br><br>", $view);

        $response = (object)$this->client->get('/lock');
        $url = implode($response->getHeader('Location'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/greet', $url);

        $response = (object)$this->client->get($url);
        $view = (string)$response->getBody();

        $this->assertContains("<h3 class=\"center\">Welcome $user Assignor</h3>", $view);
        $this->assertContains("<h3 class=\"center\">The schedule is presently <span style=\"color:#CC0000\">locked</span><br><br>", $view);
    }

    /**
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function testShowMRAsAdmin()
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
        $user = $this->config['admin_test']['user'];
        $projectKey = $this->config['admin_test']['projectKey'];
        $this->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $this->client->returnAsResponseObject(true);

        $response = (object)$this->client->get('/hidemr');
        $url = implode($response->getHeader('Location'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/greet', $url);

        $response = (object)$this->client->get($url);
        $view = (string)$response->getBody();

        $this->assertContains("<h3 class=\"center\">Welcome $user</h3>", $view);
        $this->assertContains("Medal round assignments are:&nbsp;<span style=\"color:#CC0000\">Not Viewable</span>&nbsp;-&nbsp;(<a href=/showmr>Show Medal Round Assignments</a> to users)", $view);

        $response = (object)$this->client->get('/showmr');
        $url = implode($response->getHeader('Location'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/greet', $url);

        $response = (object)$this->client->get($url);
        $view = (string)$response->getBody();

        $this->assertContains("<h3 class=\"center\">Welcome $user</h3>", $view);
        $this->assertContains("Medal round assignments are:&nbsp;<span style=\"color:#02C902\">Viewable</span>&nbsp;-&nbsp;(<a href=/hidemr>Hide Medal Round Assignments</a> from users)", $view);
    }

    /**
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function testShowMRDAsAdmin()
    {
        // instantiate the view

        $view = new MedalRoundDivisionsView($this->c, $this->sr);

        // instantiate the Unlock controller & test it

        $controller = new ShowMedalRoundDivisionsController($this->c, $view);
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

        $response = (object)$this->client->get('/hidemrd');
        $url = implode($response->getHeader('Location'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/greet', $url);

        $response = (object)$this->client->get($url);
        $view = (string)$response->getBody();

        $this->assertContains("<h3 class=\"center\">Welcome $user</h3>", $view);
        $this->assertContains("Medal round divisions are:&nbsp;<span style=\"color:#CC0000\">Not Viewable</span>&nbsp;-&nbsp;(<a href=/showmrd>Show Medal Round Divisions</a> to users)", $view);

        $response = (object)$this->client->get('/showmrd');
        $url = implode($response->getHeader('Location'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/greet', $url);

        $response = (object)$this->client->get($url);
        $view = (string)$response->getBody();

        $this->assertContains("<h3 class=\"center\">Welcome $user</h3>", $view);
        $this->assertContains("Medal round divisions are:&nbsp;<span style=\"color:#02C902\">Viewable</span>&nbsp;-&nbsp;(<a href=/hidemrd>Hide Medal Round Divisions</a> from users)", $view);
    }

    /**
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function xtestHideMRAsUser()
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

        $response = (object)$this->client->get('/showmr');
        $url = implode($response->getHeader('Location'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/greet', $url);

        $response = (object)$this->client->get($url);
        $view = (string)$response->getBody();

        $this->assertContains("<h3 class=\"center\">Welcome $user Assignor</h3>", $view);
        $response = (object)$this->client->get('/showmr');
        $url = implode($response->getHeader('Location'));

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/greet', $url);

        $response = (object)$this->client->get($url);
        $view = (string)$response->getBody();

        $this->assertContains("<h3 class=\"center\">Welcome $user Assignor</h3>", $view);
    }

    /**
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function xtestHideAsAnonymous()
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

    /**
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function xtestShowAsAnonymous()
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