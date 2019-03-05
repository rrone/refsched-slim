<?php
namespace Tests;

use App\Action\AbstractController;
use App\Action\AbstractView;
use App\Action\Full\SchedFullController;
use App\Action\Full\SchedFullView;

class FullTest extends AppTestCase
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

        $this->client = new AppWebTestClient($this->app);

    }

    /**
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function testFullAsAnonymous()
    {
        // instantiate the view and test it

        $view = new SchedFullView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedFullController($this->c, $view);
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

    /**
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function testFullAsUser()
    {
        // instantiate the view and test it

        $view = new SchedFullView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedFullController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->config['user_test']['user'];
        $projectKey = $this->config['user_test']['projectKey'];

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

    /**
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function testFullAsAdmin()
    {
        // instantiate the view and test it

        $view = new SchedFullView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedFullController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->config['admin_test']['user'];
        $projectKey = $this->config['admin_test']['projectKey'];

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

    /**
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function testFullExportAsAnonymous()
    {
        // instantiate the view and test it

        $view = new SchedFullView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedFullController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/fullexport');
        $view = (string)$response->getBody();
        $this->assertEquals('', $view);

        $url = implode($response->getHeader('Location'));
        $this->assertEquals('/full', $url);
    }

    /**
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function testFullExportAsUser()
    {
        // instantiate the view and test it

        $view = new SchedFullView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedFullController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->config['user_test']['user'];
        $projectKey = $this->config['user_test']['projectKey'];

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

    /**
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function testFullExportAsAdmin()
    {
        // instantiate the view and test it

        $view = new SchedFullView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedFullController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->config['admin_test']['user'];
        $projectKey = $this->config['admin_test']['projectKey'];

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