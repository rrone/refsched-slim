<?php
// DIC configuration

$container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Twig
$container['view'] = function ($c) {
    $settings = $c->get('settings');
    $view = new Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());
    
    return $view;
};

// Flash messages
$container['flash'] = function ($c) {
    return new Slim\Flash\Messages;
};

unset($container['errorHandler']);
//$container['errorHandler'] = function ($c) {
//    if ($c['settings']['debug']) {
//        return;
//    }
//
//    return function ($request, $response, $exception) use ($c) {
//
//    var_dump($exception);                             
//
//        return $c['response']->withStatus(500)
//                             ->withHeader('Content-Type', 'text/html')
//                             ->write($exception->xdebug_message);
//        //// 404.html, or 40x.html, or 4xx.html, or error.html
//        //
//        //$templates = array(
//        //    'errors/'.$exception.'.html.twig',
//        //    'errors/'.substr($exception, 0, 2).'x.html.twig',
//        //    'errors/'.substr($exception, 0, 1).'xx.html.twig',
//        //    'errors/default.html.twig',
//        //);
//        //
//        //return new Response($container['view']->resolveTemplate($templates)->render(array('code' => $exception)), $exception);
//    };
//};

// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    
    //Added to remove empty brackets
    //Reference: http://stackoverflow.com/questions/13968967/how-not-to-show-last-bracket-in-a-monolog-log-line
    $handler = new Monolog\Handler\StreamHandler($settings['logger']['path'], Monolog\Logger::DEBUG);
    // the last "true" here tells it to remove empty []'s
    $formatter = new Monolog\Formatter\LineFormatter(null, null, false, true);
    $handler->setFormatter($formatter);
    //End of added
    
    $logger->pushHandler($handler);
    return $logger;
};

$container['db'] = function ($c) {
    $capsule = new Illuminate\Database\Capsule\Manager;

    $capsule->addConnection($c['settings']['db']);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

$container['tm'] = function ($c) {
    $db = $c->get('db');
    $repo = new \App\Action\SchedulerRepository($db);

    return new App\Action\SessionManager($repo);
};

// -----------------------------------------------------------------------------
// Action dependency Injection
// -----------------------------------------------------------------------------

$container[App\Action\SchedulerRepository::class] = function ($c) {
    $db = $c->get('db');

    return new \App\Action\SchedulerRepository($db);
};

$container[App\Action\Logon\LogonDBController::class] = function ($c) {
    $db = $c->get('db');
    $repo = new \App\Action\SchedulerRepository($db);

    $tm = $c->get('tm');

    return new \App\Action\Logon\LogonDBController($c, $repo, $tm);
};

$container[App\Action\Greet\SchedGreetDBController::class] = function ($c) {
    $db = $c->get('db');
    $repo = new \App\Action\SchedulerRepository($db);

    return new \App\Action\Greet\SchedGreetDBController($c, $repo);
};

$container[App\Action\Sched\SchedSchedDBController::class] = function ($c) {
    $db = $c->get('db');
    $repo = new \App\Action\SchedulerRepository($db);

    return new \App\Action\Sched\SchedSchedDBController($c, $repo);
};

$container[App\Action\Full\SchedFullDBController::class] = function ($c) {
    $db = $c->get('db');
    $repo = new \App\Action\SchedulerRepository($db);

    return new \App\Action\Full\SchedFullDBController($c, $repo);
};

$container[App\Action\Master\SchedMasterDBController::class] = function ($c) {
    $db = $c->get('db');
    $repo = new \App\Action\SchedulerRepository($db);

    return new \App\Action\Master\SchedMasterDBController($c, $repo);
};

$container[App\Action\Control\SchedControlDBController::class] = function ($c) {
    $db = $c->get('db');
    $repo = new \App\Action\SchedulerRepository($db);

    return new \App\Action\Control\SchedControlDBController($c, $repo);
};

$container[App\Action\Lock\SchedLockDBController::class] = function ($c) {
    $db = $c->get('db');
    $repo = new \App\Action\SchedulerRepository($db);

    return new \App\Action\Lock\SchedLockDBController($c, $repo);
};

$container[App\Action\Lock\SchedUnlockDBController::class] = function ($c) {
    $db = $c->get('db');
    $repo = new \App\Action\SchedulerRepository($db);

    return new \App\Action\Lock\SchedUnlockDBController($c, $repo);
};

$container[App\Action\Refs\SchedRefsDBController::class] = function ($c) {
    $db = $c->get('db');
    $repo = new \App\Action\SchedulerRepository($db);

    return new \App\Action\Refs\SchedRefsDBController($c, $repo);
};

$container[App\Action\EditRef\SchedEditRefDBController::class] = function ($c) {
    $db = $c->get('db');
    $repo = new \App\Action\SchedulerRepository($db);

    return new \App\Action\EditRef\SchedEditRefDBController($c, $repo);
};

$container[App\Action\Assign\SchedAssignDBController::class] = function ($c) {
    $db = $c->get('db');
    $repo = new \App\Action\SchedulerRepository($db);

    return new \App\Action\Assign\SchedAssignDBController($c, $repo);
};

$container[App\Action\Full\SchedExportController::class] = function ($c) {
    $db = $c->get('db');
    $repo = new \App\Action\SchedulerRepository($db);
    $exporter = new \App\Action\AbstractExporter('xls');

    return new \App\Action\Full\SchedExportController($c, $repo, $exporter);
};

$container[App\Action\Admin\AdminController::class] = function ($c) {
    $db = $c->get('db');
    $repo = new \App\Action\SchedulerRepository($db);

    return new \App\Action\Admin\AdminController($c, $repo);
};

$container[App\Action\Admin\SchedTemplateExportController::class] = function ($c) {
    $db = $c->get('db');
    $repo = new \App\Action\SchedulerRepository($db);
    $exporter = new \App\Action\AbstractExporter('xls');

    return new \App\Action\Admin\SchedTemplateExportController($c, $repo, $exporter);
};

$container[App\Action\Admin\SchedImportController::class] = function ($c) {
    $db = $c->get('db');
    $repo = new \App\Action\SchedulerRepository($db);
    $importer = new \App\Action\AbstractImporter('csv');
    $uploadPath = $c->get('settings')['upload_path'];

    return new \App\Action\Admin\SchedImportController($c, $repo, $importer, $uploadPath);
};

$container[App\Action\SessionManager::class] = function ($c) {
    $db = $c->get('db');
    $repo = new \App\Action\SchedulerRepository($db);

    return new \App\Action\SessionManager($repo);
};

