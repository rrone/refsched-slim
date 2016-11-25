<?php
namespace Tests;

use There4\Slim\Test\WebTestClient;
use App\Action\AbstractController;
use App\Action\AbstractView;
use App\Action\Full\SchedFullDBController;
use App\Action\Full\SchedFullView;

class FullTest extends AppTestCase
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

        $this->client = new WebTestClient($this->app);

    }

    public function testUserFull()
    {
        // instantiate the view and test it

        $view = new SchedFullView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedFullDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->local['user_test']['user'];
        $projectKey = $this->local['user_test']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $view = $this->client->get('/full');

        $this->assertContains("<a href=/full?open>View schedule with open slots</a>",$view);
        $this->assertContains("<a  href=/sched>Go to $user schedule</a>",$view);

        $params = ['open' => '1'];
        $view = $this->client->get('/full?open', $params);

        $this->assertContains("/full>View full schedule</a>",$view);
        $this->assertContains("<a  href=/sched>Go to $user schedule</a>",$view);
    }

}