<?php
namespace Tests;

use App\Action\AbstractController;
use App\Action\AbstractView;
use App\Action\PDF\PDFController;
use App\Action\PDF\ExportPDF;

class PDFTest extends AppTestCase
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

    public function testVC()
    {
        // instantiate the view and test it

        $view = new ExportPDF();
        $this->assertNotNull($view);

        // instantiate the controller

        $controller = new PDFController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);
    }

    public function testFieldMapAsAnonymous()
    {
        // invoke the controller action and test it

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/fieldmap');
        $view = (string)$response->getBody();
        $this->assertEquals('', $view);

        $url = implode($response->getHeader('Location'));

        $this->assertEquals('/greet', $url);

    }

    public function testFieldMapAsUser()
    {
        // invoke the controller action and test it

        $user = $this->local['admin_test']['user'];
        $projectKey = $this->local['admin_test']['projectKey'];
        $event = $this->sr->getEvent($projectKey);

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $event
        ];

        $field_map = $event->field_map;

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/fieldmap');
        $view = (string)$response->getBody();
        $this->assertEquals('', $view);

        $url = implode($response->getHeader('Location'));
        $this->assertEquals('', $url);

        $contentType = $response->getHeader('Content-Type')[0];
        $cType = 'application/pdf';
        $this->assertEquals($cType, $contentType);

        $contentDisposition = $response->getHeader('Content-Disposition')[0];

        $this->assertContains("inline; filename=$field_map", $contentDisposition);

    }

    public function testFieldMapAsAdmin()
    {
        // invoke the controller action and test it

        $user = $this->local['admin_test']['user'];
        $projectKey = $this->local['admin_test']['projectKey'];
        $event = $this->sr->getEvent($projectKey);

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $event
        ];

        $field_map = $event->field_map;

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/fieldmap');
        $view = (string)$response->getBody();
        $this->assertEquals('', $view);

        $url = implode($response->getHeader('Location'));
        $this->assertEquals('', $url);

        $contentType = $response->getHeader('Content-Type')[0];
        $cType = 'application/pdf';
        $this->assertEquals($cType, $contentType);

        $contentDisposition = $response->getHeader('Content-Disposition')[0];
        $this->assertContains("inline; filename=$field_map", $contentDisposition);
    }
}