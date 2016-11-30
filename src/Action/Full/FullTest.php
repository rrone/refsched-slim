<?php
namespace Tests;

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

        $this->client = new AppWebTestClient($this->app);

    }

    public function testFullAsAnonymous()
    {
        // instantiate the view and test it

        $view = new SchedFullView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedFullDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/full');
        $view = (string)$response->getBody();
        $this->assertEquals('', $view);

        $url = implode($response->getHeader('Location'));
        $this->assertEquals('/', $url);

        $response = (object)$this->client->get('/full?open');
        $view = (string)$response->getBody();
        $this->assertEquals('', $view);

        $url = implode($response->getHeader('Location'));
        $this->assertEquals('/', $url);

    }

    public function testFullAsUser()
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

    public function testFullAsAdmin()
    {
        // instantiate the view and test it

        $view = new SchedFullView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedFullDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->local['admin_test']['user'];
        $projectKey = $this->local['admin_test']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $view = $this->client->get('/full');

        $this->assertContains("<a href=/full?open>View schedule with open slots</a>",$view);
        $this->assertContains("<a  href=/master>Select Assignors</a>",$view);
        $this->assertContains("<a  href=/sched>View Assignors</a>",$view);

        $params = ['open' => '1'];
        $view = $this->client->get('/full', $params);

        $this->assertContains("/full>View full schedule</a>",$view);
        $this->assertContains("<a  href=/master>Select Assignors</a>",$view);
        $this->assertContains("<a  href=/sched>View Assignors</a>",$view);
    }

    public function testFullExportAsAnonymous()
    {
        // instantiate the view and test it

        $view = new SchedFullView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedFullDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/fullexport');
        $view = (string)$response->getBody();
        $this->assertEquals('', $view);

        $url = implode($response->getHeader('Location'));
        $this->assertEquals('/full', $url);
    }

    public function testFullExportAsUser()
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

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/fullexport');

        $contentType = $response->getHeader('Content-Type')[0];
        $cType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $this->assertEquals($cType, $contentType);

        $contentDisposition = $response->getHeader('Content-Disposition')[0];
        $this->assertContains('attachment; filename=GameSchedule', $contentDisposition);
        $this->assertContains('.xlsx', $contentDisposition);
    }


}