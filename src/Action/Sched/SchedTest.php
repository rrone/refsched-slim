<?php
namespace Tests;

use App\Action\Sched\SchedSchedDBController;
use App\Action\Sched\SchedSchedView;
use App\Action\AbstractController;
use App\Action\AbstractView;

class SchedSchedTest extends AppTestCase
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

    public function testSchedAsAnonymous()
    {
        // instantiate the view and test it

        $view = new SchedSchedView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedSchedDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/sched');
        $url = implode($response->getHeader('Location'));

        $this->assertEquals('/', $url);
    }

    public function testSchedAsUser()
    {
        // instantiate the view and test it

        $view = new SchedSchedView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedSchedDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->local['user_test']['user'];
        $projectKey = $this->local['user_test']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $view = $this->client->get('/sched');

        $this->assertContains("<h3 class=\"center\">$user Schedule</h3>", $view);
        $this->assertContains("<a href=/refs>Edit $user referee assignments</a>", $view);
    }

    public function testSchedAsAdmin()
    {
        // instantiate the view and test it

        $view = new SchedSchedView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedSchedDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->local['admin_test']['user'];
        $projectKey = $this->local['admin_test']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $view = $this->client->get('/sched');

        $this->assertContains("<h3 class=\"center\">$user Schedule</h3>",$view);
        $this->assertContains("<a href=/refs>Edit referee assignments</a>",$view);
    }

    public function testRepostSchedAsAdmin()
    {
        // instantiate the view and test it

        $view = new SchedSchedView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedSchedDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->local['user_test']['user'];
        $projectKey = $this->local['user_test']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey),
        ];

        $this->sr->updateAssignor([457=>$user]);
        $games = $this->sr->getGamesByRep($projectKey,$user);

        $this->sr->updateAssignor([501=>'']);
        $unassignedGames = $this->sr->getUnassignedGames($projectKey, 'U16');

        $url = '/sched';
        $headers = array(
            'cache-control' => 'no-cache',
            'content-type' => 'multipart/form-data;'
        );
        $body = array(
            'Submit' => 'Submit',
            'group' => '',
        );

        $body['assign:'. $unassignedGames[0]->id] = $unassignedGames[0]->id;
        foreach ($games as $k => $game) {
            if ($k > 0) {
                $body['games:' . $game->id] = $game->id;
            }
        }

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->post($url, $body, $headers);
        $view = (string)$response->getBody();

        $this->assertContains("<h3 class=\"center\">$user Schedule</h3>", $view);
        $this->assertContains("<input class=\"btn btn-primary btn-xs right \" type=\"submit\"", $view);
        $this->assertContains("value=\"Submit\">", $view);
    }

    public function testSchedGroupU16AsUser()
    {
        // instantiate the view and test it

        $view = new SchedSchedView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedSchedDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->local['user_test']['user'];
        $projectKey = $this->local['user_test']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $params = ['group' => 'U16'];
        $view = $this->client->get('/sched', $params);

        $this->assertContains("<h3 class=\"center\">$user Schedule</h3>", $view);
        $this->assertContains("<a href=/sched>View all $user games</a>", $view);
    }

}