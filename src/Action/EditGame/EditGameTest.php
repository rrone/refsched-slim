<?php
namespace Tests;

use App\Action\EditGame\EditGameController;
use App\Action\EditGame\EditGameView;
use App\Action\AbstractController;
use App\Action\AbstractView;


class EditGameTest extends AppTestCase
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
     *
     */
    public function testEditGameAsUser()
    {
        // instantiate the view and test it

        $view = new EditGameView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new EditGameController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->config['user_test']['user'];
        $projectKey = $this->config['testParams']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/editgame');
        $url = implode($response->getHeader('Location'));

        $this->assertEquals('/greet', $url);
    }

    /**
     *
     */
    public function testEditGameAsAdmin()
    {
        // instantiate the view and test it

        $view = new EditGameView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new EditGameController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->config['admin_test']['user'];
        $projectKey = $this->config['testParams']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/editgame');
        $view = (string)$response->getBody();

        $this->assertContains("<input class=\"btn btn-primary btn-xs right\" type=\"submit\" name=\"action\" value=\"Update Matches\">", $view);
    }

    /**
     *
     */
    public function testGamePostAsAdmin()
    {
        // instantiate the view and test it

        $view = new EditGameView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new EditGameController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->config['admin_test']['user'];
        $projectKey = $this->config['testParams']['projectKey'];
        $event = $this->sr->getEvent($projectKey);

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $event,
        ];
        $this->client->returnAsResponseObject(true);

        // reset edit names
        $url = '/editgame';
        $headers = array(
            'cache-control' => 'no-cache',
            'content-type' => 'multipart/form-data;'
        );
        $body = array(
            0 => 'Update Matches',
            '1912+projectKey' => $projectKey,
            '1912+id' => '1912',
            '1912+game_number' => '2',
            '1912+away' => 'C2--test',
        );

        $response = (object)$this->client->post($url, $body, $headers);
        $view = (string)$response->getBody();
        $this->assertContains("<td><input type=\"text\" name=\"1912+away\" value=\"C2--test\"></td>", $view);

        // reset edit names
        $body = array(
            0 => 'Update Matches',
            '1912+projectKey' => $projectKey,
            '1912+id' => '1912',
            '1912+game_number' => '2',
            '1912+away' => 'C2',
        );

        $response = (object)$this->client->post($url, $body, $headers);
        $view = (string)$response->getBody();
        $this->assertContains("<td><input type=\"text\" name=\"1912+away\" value=\"C2\"></td>", $view);
    }


}