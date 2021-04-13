<?php
namespace Tests;

use App\Action\EditRef\SchedEditRefController;
use App\Action\EditRef\SchedEditRefView;
use App\Action\AbstractController;
use App\Action\AbstractView;

class EditRefTest extends AppTestCase
{
    protected $goodId = 2;
    protected $badId = 10000;

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
    public function testEditRefAsAnonymous()
    {
        // instantiate the view and test it

        $view = new SchedEditRefView($this->c, $this->sr, $this->p);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedEditRefController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/editref');
        $view = (string)$response->getBody();
        $this->assertEquals('', $view);

        $url = implode($response->getHeader('Location'));
        $this->assertEquals('/', $url);
    }

    /**
     *
     */
    public function testEditRefAsUser()
    {
        // instantiate the view and test it

        $view = new SchedEditRefView($this->c, $this->sr, $this->p);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedEditRefController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->config['user_test']['user'];
        $projectKey = $this->config['testParams']['projectKey'];

        $game = $this->sr->getGameByKeyAndNumber($projectKey, $this->goodId);
        $game_id = $game->id;

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey),
            'game_id' => $game_id
        ];

        $url = '/refs';
        $headers = array(
            'cache-control' => 'no-cache',
            'content-type' => 'multipart/form-data;'
        );
        $body = array(
            'event' => $this->sr->getEvent($projectKey),
            'user' => $user,
            'game_id' => $game_id
        );

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->post($url, $body, $headers);

        $url = implode($response->getHeader('Location'));
        $this->assertEquals('/editref', $url);

        $response = (object)$this->client->get($url);
        $view = (string)$response->getBody();

        $this->assertContains("<h3 class=\"center\">Assign $user Referees</h3>", $view);
        $this->assertContains("<input class=\"btn btn-primary btn-xs right\" type=\"submit\" name=\"1912\" value=\"Update Assignments\">", $view);
        $this->assertContains("value=\"Update Assignments\">", $view);

        // test edit names
        $url = '/editref';
        $body = array(
            'cr' => 'Last, CRFirst',
            'ar1' => 'AR1FIRST LAST',
            'ar2' => 'AR2First Last',
            $game_id => 'Update Assignments'
        );

        $response = (object)$this->client->post($url, $body, $headers);
        $url = implode($response->getHeader('Location'));
        $this->assertEquals('/refs', $url);

        $response = (object)$this->client->get($url);
        $view = (string)$response->getBody();

        $this->assertContains("<td>CRFirst Last</td><td>AR1FIRST Last</td><td>AR2First Last</td>", $view);

        //clear edit names
        $url = '/editref';
        $body = array(
            'cr' => '',
            'ar1' => '',
            'ar2' => '',
            $game_id => 'Update Assignments'
        );

        $this->client->post($url, $body, $headers);

    }

    /**
     *
     */
    public function testEditRefAsAdmin()
    {
        // instantiate the view and test it

        $view = new SchedEditRefView($this->c, $this->sr, $this->p);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedEditRefController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->config['admin_test']['user'];
        $projectKey = $this->config['testParams']['projectKey'];
        $game_id = $this->sr->getGameByKeyAndNumber($projectKey, $this->goodId)->id;

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey),
            'game_id' => $game_id
        ];

        $url = '/refs';
        $headers = array(
            'cache-control' => 'no-cache',
            'content-type' => 'multipart/form-data;'
        );
        $body = array(
            'event' => $this->sr->getEvent($projectKey),
            'user' => $user,
            'game_id' => $game_id
        );

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->post($url, $body, $headers);

        $url = implode($response->getHeader('Location'));
        $this->assertEquals('/editref', $url);

        $response = (object)$this->client->get($url);
        $view = (string)$response->getBody();

        $this->assertContains("<h3 class=\"center\">Assign $user Referees</h3>", $view);
        $this->assertContains("<input class=\"btn btn-primary btn-xs right\" type=\"submit\"", $view);
        $this->assertContains("value=\"Update Assignments\">", $view);

        // test edit names
        $url = '/editref';
        $body = array(
            'cr' => 'Referee Admin Last',
            'ar1' => 'AR1 Admin Last',
            'ar2' => 'AR2 Admin Last',
            $game_id => 'Update Assignments'
        );

        $response = (object)$this->client->post($url, $body, $headers);
        $url = implode($response->getHeader('Location'));
        $this->assertEquals('/refs', $url);

        $response = (object)$this->client->get($url);
        $view = (string)$response->getBody();

        $this->assertContains("<td>Referee Admin Last</td><td>AR1 Admin Last</td><td>AR2 Admin Last</td>", $view);

        //clear edit names
        $url = '/editref';
        $body = array(
            'cr' => '',
            'ar1' => '',
            'ar2' => '',
            $game_id => 'Update Assignments'
        );

        $this->client->post($url, $body, $headers);
    }

    /**
     *
     */
    public function testEditRefBadGameID()
    {
        // instantiate the view and test it

        $view = new SchedEditRefView($this->c, $this->sr, $this->p);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new SchedEditRefController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->config['user_test']['user'];
        $projectKey = $this->config['testParams']['projectKey'];
        $game_id = $this->badId;

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey),
            'game_id' => $game_id
        ];

        $url = '/refs';
        $headers = array(
            'cache-control' => 'no-cache',
            'content-type' => 'multipart/form-data;'
        );
        $body = array(
            'cr' => '',
            'ar1' => '',
            'ar2' => '',
            $game_id => 'Update Assignments'
        );

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->post($url, $body, $headers);
        $url = implode($response->getHeader('Location'));

        $this->assertEquals('/editref', $url);

        $response = (object)$this->client->get($url);
        $view = (string)$response->getBody();

        $this->assertContains("The matching match was not found or your Area was not assigned to it.<br>You might want to check the schedule and try again.</span></h3>", $view);

    }
}