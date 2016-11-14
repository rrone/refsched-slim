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

define('PROJECT_ROOT', realpath(__DIR__ . '/..'));

require_once PROJECT_ROOT . '/vendor/autoload.php';
// Initialize our own copy of the slim application
abstract class LocalWebTestCase extends WebTestCase
{
    public function getSlimInstance() {

        $local = include(PROJECT_ROOT . '/config/local.php');

// Instantiate the app
        $settings = require PROJECT_ROOT . '/app/settings.php';
        $settings['debug'] = true;
        $settings['settings']['db'] = $local['db_test'];
//Define where the log goes: syslog

        $app = new App($settings);

// Set up dependencies
        require PROJECT_ROOT . '/app/dependencies.php';

// Register middleware
        require PROJECT_ROOT . '/app/middleware.php';

// Register routes
        require PROJECT_ROOT . '/app/routes.php';

        $c = $app->getContainer();
        $sr = new SchedulerRepository($c->get('db'));
        $c['settings.test'] = true;
        $c['session'] = [
            'authed' => true,
            'user' => $sr->getUserByName('Area 1B'),
            'event' => $sr->getEvent('2016U16U19Chino')
        ];

        return $app;
    }
};

/* End of file bootstrap.php */