<?php
namespace Tests;

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

// Initialize our own copy of the slim application
class AppTestCase extends WebTestCase
{
    protected $local;

    /**
     * @var SchedulerRepository
     */
    protected $sr;

    /**
     * @var Container
     */
    protected $c;

    /* @var \Tests\AppWebTestClient */
    protected $client;

    private $cookies = array();

    public function getSlimInstance() {

        $this->local = include(PROJECT_ROOT . '/config/local.php');

// Instantiate the app
        $settings = require PROJECT_ROOT . '/app/settings.php';
        $settings['debug'] = true;
        $settings['settings']['db'] = $this->local['db_test'];

        $app = new \Slim\App($settings);

// Set up dependencies
        require PROJECT_ROOT . '/app/dependencies.php';

// Register middleware
        require PROJECT_ROOT . '/app/middleware.php';

// Register routes
        require PROJECT_ROOT . '/app/routes.php';

        return $app;
    }
};

/* End of file bootstrap.php */
