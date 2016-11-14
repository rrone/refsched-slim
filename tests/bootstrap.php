<?php
namespace Tests;

session_start();

// Settings to make all errors more obvious during testing
error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_log', 'syslog');
date_default_timezone_set('UTC');

use Slim\App;
use App\Action\SchedulerRepository;
use There4\Slim\Test\WebTestCase;
use Slim\Http\Environment;
use Slim\Http\Uri;
use Slim\Http\Headers;
use Slim\Http\RequestBody;
use Slim\Http\UploadedFile;
use Slim\Http\Request;

define('PROJECT_ROOT', realpath(__DIR__ . '/..'));

require_once PROJECT_ROOT . '/vendor/autoload.php';

// Initialize our own copy of the slim application
abstract class AppTestCase extends WebTestCase
{
    protected $local;
    protected $sr;
    protected $c;

    public function getSlimInstance() {

        $this->local = include(PROJECT_ROOT . '/config/local.php');

// Instantiate the app
        $settings = require PROJECT_ROOT . '/app/settings.php';
        $settings['debug'] = true;
        $settings['settings']['db'] = $this->local['db_test'];
//Define where the log goes: syslog

        $app = new App($settings);

// Set up dependencies
        require PROJECT_ROOT . '/app/dependencies.php';

// Register middleware
        require PROJECT_ROOT . '/app/middleware.php';

// Register routes
        require PROJECT_ROOT . '/app/routes.php';

        $this->c = $app->getContainer();
        $this->sr = new SchedulerRepository($this->c->get('db'));
        $app->getContainer()['settings.test'] = true;

        return $app;
    }

    public function requestFactory($_uri, $_method, $_headers, $_cookies, $_serverParams, $_body)
    {
        $env = Environment::mock();
        $uri = Uri::createFromString($_uri);
        $headers = Headers::createFromEnvironment($env);
        foreach($_headers as $h){
            $headers[] = $h;
        }
        $cookies = [];
        $serverParams = $env->all();
        $body = new RequestBody();
        $body = $body->write($_body);
        $uploadedFiles = UploadedFile::createFromEnvironment($env);
        $request = new Request($_method, $uri, $headers, $cookies, $serverParams, $body, $uploadedFiles);

        return $request;
    }
};

/* End of file bootstrap.php */