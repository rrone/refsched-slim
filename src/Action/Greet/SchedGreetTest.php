<?php
namespace Tests;

use There4\Slim\Test\WebTestClient;
use App\Action\AbstractController;
use App\Action\AbstractView;
use App\Action\Greet\SchedGreetDBController;
use App\Action\Greet\GreetView;

class SchedGreetTest extends AppTestCase
{
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

    }

    public function testGreetAsAnonymous()
    {
        // instantiate the view and test it

        $view = new GreetView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedGreetDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/greet');
        $url = implode($response->getHeader('Location'));

        $this->assertEquals('/', $url);
    }

    public function testGreetAsUser()
    {
        // instantiate the view and test it

        $view = new GreetView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedGreetDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->local['user_test']['user'];
        $projectKey = $this->local['user_test']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $view = $this->client->post('/greet');
        $this->assertContains("<h3 class=\"center\">Welcome $user Assignor</h3>",$view);
    }

    public function testGreetAsAdmin()
    {
        // instantiate the view and test it

        $view = new GreetView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedGreetDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it
        $user = $this->local['admin_test']['user'];
        $projectKey = $this->local['admin_test']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $view = $this->client->get('/greet');
        $this->assertContains("<h3 class=\"center\">Welcome $user Assignor</h3>",$view);
        $this->assertContains("<h3 class=\"center\"><a href=/editgame>Edit games</a>",$view);
    }

}