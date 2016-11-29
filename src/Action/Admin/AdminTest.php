<?php
namespace Tests;

use App\Action\AbstractImporter;
use App\Action\Admin\AdminController;
use App\Action\Admin\AdminView;
use App\Action\AbstractController;
use App\Action\AbstractView;
use App\Action\AbstractExporter;
use App\Action\Admin\LogExportController;
use App\Action\Admin\LogExport;
use App\Action\Admin\SchedTemplateExport;
use App\Action\Admin\SchedTemplateExportController;
use App\Action\Admin\SchedImport;
use App\Action\Admin\SchedImportController;

class AdminTest extends AppTestCase
{
    protected $testUri;

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

        $this->testUri = '/adm';

    }

    public function testAdminAsAnonymous()
    {
        // instantiate the view and test it

        $view = new AdminView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new AdminController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get($this->testUri);
        $url = implode($response->getHeader('Location'));

        $this->assertEquals('/greet', $url);
    }

    public function testAdminAsUser()
    {
        // instantiate the view and test it

        $view = new AdminView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new AdminController($this->c, $view);
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
        $response = (object)$this->client->get($this->testUri);
        $url = implode($response->getHeader('Location'));

        $this->assertEquals('/greet', $url);
    }

    public function testAdminAsAdmin()
    {
        // instantiate the view and test it

        $view = new AdminView($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractView);

        // instantiate the controller

        $controller = new AdminController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->local['admin_test']['user'];
        $projectKey = $this->local['user_test']['projectKey'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)
        ];

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get($this->testUri);
        $view = (string)$response->getBody();

        $this->assertContains("<h1>Administrative Functions</h1>", $view);
    }

    public function testLogExportAsUser()
    {
        // instantiate the view and test it

        $view = new LogExport($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractExporter);

        // instantiate the controller

        $controller = new LogExportController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->local['user_test']['user'];
        $projectKey = $this->local['user_test']['projectKey'];

        $this->client->app->getContainer()['session'] = ['authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)];

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/adm/log');

        $url = implode($response->getHeader('Location'));

        $this->assertEquals('/greet', $url);
    }

    public function testLogExportAsAdmin()
    {
        // instantiate the view and test it

        $view = new LogExport($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractExporter);

        // instantiate the controller

        $controller = new LogExportController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->local['admin_test']['user'];
        $projectKey = $this->local['admin_test']['projectKey'];

        $this->client->app->getContainer()['session'] = ['authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)];

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/adm/log');

        $contentType = $response->getHeader('Content-Type')[0];
        $cType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $this->assertEquals($cType, $contentType);

        $contentDisposition = $response->getHeader('Content-Disposition')[0];
        $this->assertContains('attachment; filename=Access_Log', $contentDisposition);
        $this->assertContains('.xlsx', $contentDisposition);
    }

    public function testTemplateExportAsUser()
    {
        // instantiate the view and test it

        $view = new SchedTemplateExport($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractExporter);

        // instantiate the controller

        $controller = new SchedTemplateExportController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->local['user_test']['user'];
        $projectKey = $this->local['user_test']['projectKey'];

        $this->client->app->getContainer()['session'] = ['authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)];

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/adm/template');

        $url = implode($response->getHeader('Location'));

        $this->assertEquals('/greet', $url);
    }

    public function testTemplateExportAsAdmin()
    {
        // instantiate the view and test it

        $view = new SchedTemplateExport($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractExporter);

        // instantiate the controller

        $controller = new SchedTemplateExportController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->local['admin_test']['user'];
        $projectKey = $this->local['admin_test']['projectKey'];

        $this->client->app->getContainer()['session'] = ['authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)];

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/adm/template');

        $contentType = $response->getHeader('Content-Type')[0];
        $cType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $this->assertEquals($cType, $contentType);

        $contentDisposition = $response->getHeader('Content-Disposition')[0];
        $this->assertContains('attachment; filename=GameScheduleTemplate', $contentDisposition);
        $this->assertContains('.xlsx', $contentDisposition);
    }

    public function testNullTemplateExportAsAdmin()
    {
        // instantiate the view and test it

        $view = new SchedTemplateExport($this->c, $this->sr);
        $this->assertTrue($view instanceof AbstractExporter);

        // instantiate the controller

        $controller = new SchedTemplateExportController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->local['admin_test']['user'];

        $this->client->app->getContainer()['session'] = [
            'authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => null
        ];

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/adm/template');

        $url = implode($response->getHeader('Location'));

        $this->assertEquals('/', $url);
    }

    public function testSchedImportAsUser()
    {
        // instantiate the view and test it
        $uploadPath = $this->app->getContainer()->get('settings')['upload_path'];

        $view = new SchedImport($this->c, $this->sr, $uploadPath);
        $this->assertTrue($view instanceof AbstractImporter);

        // instantiate the controller

        $controller = new SchedImportController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->local['user_test']['user'];
        $projectKey = $this->local['user_test']['projectKey'];

        $this->client->app->getContainer()['session'] = ['authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)];

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/adm/import');

        $url = implode($response->getHeader('Location'));

        $this->assertEquals('/greet', $url);
    }

    public function testSchedImportAsAdmin()
    {
        // instantiate the view and test it

        $uploadPath = $this->app->getContainer()->get('settings')['upload_path'];

        $view = new SchedImport($this->c, $this->sr, $uploadPath);
        $this->assertTrue($view instanceof AbstractImporter);

        // instantiate the controller

        $controller = new SchedImportController($this->c, $view);
        $this->assertTrue($controller instanceof AbstractController);

        // invoke the controller action and test it

        $user = $this->local['admin_test']['user'];
        $projectKey = $this->local['admin_test']['projectKey'];

        $this->client->app->getContainer()['session'] = ['authed' => true,
            'user' => $this->sr->getUserByName($user),
            'event' => $this->sr->getEvent($projectKey)];

        $this->client->returnAsResponseObject(true);
        $response = (object)$this->client->get('/adm/import');

        $view = (string)$response->getBody();

        $this->assertContains("<h1>Schedule Import</h1>", $view);
    }


}