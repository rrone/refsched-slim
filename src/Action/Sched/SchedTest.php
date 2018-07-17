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

    /**
     * @throws \Interop\Container\Exception\ContainerException
     */
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

    /**
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function testSchedAsUser()
    {
        // instantiate the view and test it

        $view = new SchedSchedView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedSchedDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->config['user_test']['user'];
        $projectKey = $this->config['user_test']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $view = $this->client->get('/sched');

        $this->assertContains("<h3 class=\"center\">$user: Schedule</h3>", $view);
        $this->assertContains("<a href=/refs>Edit $user Referee Assignments</a>", $view);
    }

    /**
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function testSchedAsAdmin()
    {
        // instantiate the view and test it

        $view = new SchedSchedView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedSchedDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->config['admin_test']['user'];
        $projectKey = $this->config['admin_test']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $view = $this->client->get('/sched');

        $this->assertContains("<h3 class=\"center\">$user: Schedule</h3>",$view);
        $this->assertContains("<a href=/refs>Edit Referee Assignments</a>",$view);
    }


    /**
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function testSchedGroup16UAsUser()
    {
        // instantiate the view and test it

        $view = new SchedSchedView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedSchedDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->config['user_test']['user'];
        $projectKey = $this->config['user_test']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $params = ['group' => 'U16'];
        $view = $this->client->get('/sched', $params);

        $this->assertContains("<h3 class=\"center\">$user: Schedule</h3>", $view);
        $this->assertContains("<a href=/sched>View all $user matches</a>", $view);
    }

    /**
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function xtestRepostSchedAsAdmin()
    {
        // instantiate the view and test it

        $view = new SchedSchedView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedSchedDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->config['user_test']['user'];
        $projectKey = $this->config['user_test']['projectKey'];
        $show_medal_round = $this->sr->getMedalRound($projectKey);

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey),
        ];

        $this->sr->updateAssignor([457=>$user]);
        $games = $this->sr->getGamesByRep($projectKey, $user, $show_medal_round);

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

        if(isset($unassignedGames[0])) {
            $body['assign:'.$unassignedGames[0]->id] = $unassignedGames[0]->id;
            foreach ($games as $k => $game) {
                if ($k > 0) {
                    $body['matches:'.$game->id] = $game->id;
                }
            }
        }

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->post($url, $body, $headers);
        $view = (string)$response->getBody();

        $this->assertContains("<h3 class=\"center\">$user: Schedule</h3>", $view);

        //test isRepost
        $response = (object)$this->client->post($url, $body, $headers);
        $view = (string)$response->getBody();

        $this->assertContains("<h3 class=\"center\">$user: Schedule</h3>", $view);
    }

    /**
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function testNoGamesAsUser()
    {
        // instantiate the view and test it

        $view = new SchedSchedView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedSchedDBController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->config['empty_test']['user'];
        $projectKey = $this->config['empty_test']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $view = $this->client->get('/sched');

        $this->assertContains("<h3 class=\"center\">$user: Schedule</h3>", $view);
        $this->assertContains("<h3 class=\"left\">0 Matches assigned to Area 1P</h3>", $view);
    }

}