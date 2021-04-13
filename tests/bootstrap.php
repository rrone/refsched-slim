<?php
namespace Tests;

// Settings to make all errors more obvious during testing
error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_log', 'syslog');
date_default_timezone_set('UTC');

use TheIconic\NameParser\Parser;
use Slim\App;
use App\Action\SchedulerRepository;
use Slim\Container;
use There4\Slim\Test\WebTestCase;
use Slim\Http\Environment;
use Slim\Http\Uri;
use Slim\Http\Headers;
use Slim\Http\RequestBody;
use Slim\Http\Request;

define('PROJECT_ROOT', realpath(__DIR__ . '/..'));

require_once PROJECT_ROOT . '/vendor/autoload.php';

session_start();

// Initialize our own copy of the slim application
class AppTestCase extends WebTestCase
{
    protected $config;

    /**
     * @var SchedulerRepository
     */
    protected $sr;

    /**
     * @var Container
     */
    protected $c;

    /**
     * @var Parser
     */
    protected $p;

    /* @var AppWebTestClient */
    protected $client;

    private $cookies = array();

    /**
     * @return App
     */
    public function getSlimInstance() {

        $this->config = include(PROJECT_ROOT . '/config/config.php');

// Instantiate the app
        $settings = require PROJECT_ROOT . '/app/settings.php';
        $settings['debug'] = true;

        $settings['settings']['dbConfig'] = $this->config['test'];

        $settings['test']['user'] = $this->config['user_test'];
        $settings['test']['admin'] = $this->config['admin_test'];
        $settings['test']['empty'] = $this->config['empty_test'];
        $settings['test']['dev'] = $this->config['dev_test'];

//Define where the log goes: syslog

        $app = new App($settings);

// Set up dependencies
        require PROJECT_ROOT . '/app/dependencies.php';

// Register middleware
        require PROJECT_ROOT . '/app/middleware.php';

// Register routes
        require PROJECT_ROOT . '/app/routes.php';

        /** @var Container c */
        $this->c = $app->getContainer();

        $this->p = $this->c['p'];
        $this->sr = new SchedulerRepository($this->c['db']);

        $app->getContainer()['settings.test'] = true;

        return $app;
    }

    /**
     * @param $method
     * @param $path
     * @param array $data
     * @param array $optionalHeaders
     * @return Request
     */
    protected function request($method, $path, $data = array(), $optionalHeaders = array())
    {
        //Make method uppercase
        $method = strtoupper($method);
        $options = array(
            'REQUEST_METHOD' => $method,
            'REQUEST_URI' => $path
        );

        if ($method === 'GET') {
            $options['QUERY_STRING'] = http_build_query($data);
        } else {
            $params = json_encode($data);
        }

        // Prepare a mock environment
        $env = Environment::mock(array_merge($options, $optionalHeaders));
        $uri = Uri::createFromEnvironment($env);
        $headers = Headers::createFromEnvironment($env);
        $cookies = $this->cookies;
        $serverParams = $env->all();
        $body = new RequestBody();

        // Attach JSON request
        if (isset($params)) {
            $headers->set('Content-Type', 'application/json;charset=utf8');
            $body->write($params);
        }

        return new Request($method, $uri, $headers, $cookies, $serverParams, $body);
    }

    /**
     * @param $name
     * @param $value
     */
    protected function setCookie($name, $value)
    {
        $this->cookies[$name] = $value;
    }
}

/* End of file bootstrap.php */