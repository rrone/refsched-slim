<?php
namespace Tests;

// To help the built-in PHP dev server, check if the request was actually for
// something which should probably be served as a static file
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

$local = include(PROJECT_ROOT . '/config/local.php');

require __DIR__ . '/../vendor/autoload.php';

// Instantiate the app
$settings = require PROJECT_ROOT . '/app/settings.php';
$settings['debug'] = true;
$settings['settings']['db'] = $local['db_test'];
$settings['session.test'] = true;

$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../app/dependencies.php';

// Register middleware
require __DIR__ . '/../app/middleware.php';

// Register routes
require __DIR__ . '/../app/routes.php';

// Run!
return $app;
