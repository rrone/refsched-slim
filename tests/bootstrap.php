<?php
namespace Tests;

session_start();

// Settings to make all errors more obvious during testing
error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_log', 'syslog');
date_default_timezone_set('UTC');

use Silex\WebTestCase;

define('PROJECT_ROOT', realpath(__DIR__ . '/..'));

require_once PROJECT_ROOT . '/vendor/autoload.php';
// Initialize our own copy of the slim application

abstract class LocalWebTestCase extends WebTestCase
{
    public function createApplication() {

        $app = require PROJECT_ROOT . '/public/app_test.php';

        return $this->app = $app;
    }
};

/* End of file bootstrap.php */

