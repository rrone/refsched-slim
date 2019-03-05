<?php
namespace Tests;

use App\Action\AbstractController;
use App\Action\AbstractView;
use App\Action\Greet\SchedGreetController;
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

    /**
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function testGreetAsAnonymous()
    {
        // instantiate the view and test it

        $view = new GreetView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedGreetController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/greet');
        $url = implode($response->getHeader('Location'));

        $this->assertEquals('/', $url);
    }

    /**
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function testGreetAsUser()
    {
        // instantiate the view and test it

        $view = new GreetView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedGreetController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->config['user_test']['user'];
        $projectKey = $this->config['user_test']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $view = $this->client->post('/greet');
        $this->assertContains("<h3 class=\"center\">Welcome $user Assignor</h3>",$view);
    }

    /**
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function testGreetAsAdmin()
    {
        // instantiate the view and test it

        $view = new GreetView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedGreetController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it
        $user = $this->config['admin_test']['user'];
        $projectKey = $this->config['admin_test']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $view = $this->client->get('/greet');
        $this->assertContains("<h3 class=\"center\">Welcome $user</h3>",$view);
        $this->assertContains("<h3 class=\"center\"><a href=/full>View full schedule</a></h3>",$view);
    }

}