<?php
namespace Tests;

use App\Action\NoEvents\NoEventsController;
use App\Action\NoEvents\NoEventsView;
use There4\Slim\Test\WebTestClient;
use App\Action\AbstractController;
use App\Action\AbstractView;

class NoEventsTest extends AppTestCase
{
    /**
     *
     */
    public function setUp()
    {
//     Setup App controller
        $this->app = $this->getSlimInstance();
        $this->app->getContainer()['session'] = [
            'authed' => false,
            'user' => null,
            'event' => null
        ];

        $this->client = new WebTestClient($this->app);

    }

    /**
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function testNoEventsAsAnonymous()
    {
        // instantiate the view and test it

        $view = new NoEventsView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new NoEventsController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $view = $this->client->get('/na');

        $this->assertContains("<h1>Section 1 Event Schedule</h1>",$view);
    }


}