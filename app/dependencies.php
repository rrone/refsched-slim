<?php
// DIC configuration

$container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Twig
$container['view'] = function (\Slim\Container $c) {
    $settings = $c->get('settings');
    $view = new Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());

    $Version = new Twig_SimpleFunction('version', function () use ($settings) {
        $ver = 'Version ' . $settings['version']['version'];

        return $ver;
    });

    $view->getEnvironment()->addFunction($Version);

    return $view;
};

// Flash messages
$container['flash'] = function () {
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
$container['logger'] = function (\Slim\Container $c) {
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

$container['sr'] = function (\Slim\Container $c) {
    $db = $c->get('db');
    $scheduleRepository = new \App\Action\SchedulerRepository($db);

    return $scheduleRepository;
};

// -----------------------------------------------------------------------------
// Action dependency Injection
// -----------------------------------------------------------------------------
$db = $container->get('db');
$sr = $container['sr'];
$view = $container->get('view');
$uploadPath = $container->get('settings')['upload_path'];

$container[App\Action\SchedulerRepository::class] = function ($db) {

    return new \App\Action\SchedulerRepository($db);
};

// -----------------------------------------------------------------------------
// Admin class
// -----------------------------------------------------------------------------
$container[App\Action\Admin\AdminView::class] = function ($c) use ($sr) {

    return new \App\Action\Admin\AdminView($c, $sr);
};

$container[App\Action\Admin\AdminController::class] = function ($c) use ($sr) {
    $v = new \App\Action\Admin\AdminView($c, $sr);

    return new \App\Action\Admin\AdminController($c, $v);
};

// -----------------------------------------------------------------------------
// Logon class
// -----------------------------------------------------------------------------
$container[App\Action\Logon\LogonView::class] = function ($c) use ($sr) {

    return new \App\Action\Logon\LogonView($c, $sr);
};

$container[App\Action\Logon\LogonDBController::class] = function ($c) use ($sr) {
    $v = new \App\Action\Logon\LogonView($c, $sr);

    return new \App\Action\Logon\LogonDBController($c, $v);
};

// -----------------------------------------------------------------------------
// Greet class
// -----------------------------------------------------------------------------
$container[App\Action\Greet\GreetView::class] = function ($c) use ($sr) {

    return new \App\Action\Greet\GreetView($c, $sr);
};

$container[App\Action\Greet\SchedGreetDBController::class] = function ($c) use ($sr) {
    $v = new \App\Action\Greet\GreetView($c, $sr);

    return new \App\Action\Greet\SchedGreetDBController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedFull class
// -----------------------------------------------------------------------------
$container[App\Action\Full\SchedFullView::class] = function ($c) use ($sr) {

    return new \App\Action\Full\SchedFullView($c, $sr);
};

$container[App\Action\Full\SchedFullDBController::class] = function ($c) use ($sr) {
    $v = new \App\Action\Full\SchedFullView($c, $sr);

    return new \App\Action\Full\SchedFullDBController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedExport class
// -----------------------------------------------------------------------------
$container[App\Action\Full\SchedExportXl::class] = function () use ($sr) {

    return new \App\Action\Full\SchedExportXl($sr);
};

$container[App\Action\Full\SchedExportController::class] = function ($c) use ($sr) {
    $v = new \App\Action\Full\SchedExportXl($sr);

    return new \App\Action\Full\SchedExportController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedSched class
// -----------------------------------------------------------------------------
$container[App\Action\Sched\SchedSchedView::class] = function ($c) use ($sr) {

    return new \App\Action\Sched\SchedSchedView($c, $sr);
};

$container[App\Action\Sched\SchedSchedDBController::class] = function ($c) use ($sr) {
    $v = new \App\Action\Sched\SchedSchedView($c, $sr);

    return new \App\Action\Sched\SchedSchedDBController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedMaster class
// -----------------------------------------------------------------------------
$container[App\Action\Master\SchedMasterView::class] = function ($c) use ($sr) {

    return new \App\Action\Master\SchedMasterView($c, $sr);
};

$container[App\Action\Master\SchedMasterDBController::class] = function ($c) use ($sr) {
    $v = new \App\Action\Master\SchedMasterView($c, $sr);

    return new \App\Action\Master\SchedMasterDBController($c, $v);
};

// -----------------------------------------------------------------------------
// Lock & Unlock classes
// -----------------------------------------------------------------------------
$container[App\Action\Lock\SchedLockView::class] = function ($c) use ($sr) {

    return new \App\Action\Lock\SchedLockView($c, $sr);
};

$container[App\Action\Lock\SchedLockDBController::class] = function ($c) use ($sr) {
    $v = new \App\Action\Lock\SchedLockView($c, $sr);

    return new \App\Action\Lock\SchedLockDBController($c, $v);
};

$container[App\Action\Lock\SchedUnlockDBController::class] = function ($c) use ($sr) {
    $v = new \App\Action\Lock\SchedLockView($c, $sr);

    return new \App\Action\Lock\SchedUnlockDBController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedRefs class
// -----------------------------------------------------------------------------
$container[App\Action\Refs\SchedRefsView::class] = function ($c) use ($sr) {

    return new \App\Action\Refs\SchedRefsView($c, $sr);
};

$container[App\Action\Refs\SchedRefsDBController::class] = function ($c) use ($sr) {
    $v = new \App\Action\Refs\SchedRefsView($c, $sr);

    return new \App\Action\Refs\SchedRefsDBController($c, $v);
};

// -----------------------------------------------------------------------------
// EditRef class
// -----------------------------------------------------------------------------
$container[App\Action\EditRef\SchedEditRefView::class] = function ($c) use ($sr) {

    return new \App\Action\EditRef\SchedEditRefView($c, $sr);
};

$container[App\Action\EditRef\SchedEditRefDBController::class] = function ($c) use ($sr) {
    $v = new \App\Action\EditRef\SchedEditRefView($c, $sr);

    return new \App\Action\EditRef\SchedEditRefDBController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedTemplateExport class
// -----------------------------------------------------------------------------
$container[App\Action\Admin\SchedTemplateExport::class] = function ($c) use ($sr) {

    return new \App\Action\Admin\SchedTemplateExport($c, $sr);
};

$container[App\Action\Admin\SchedTemplateExportController::class] = function ($c) use ($sr) {
    $v = new \App\Action\Admin\SchedTemplateExport($c, $sr);

    return new \App\Action\Admin\SchedTemplateExportController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedImport class
// -----------------------------------------------------------------------------
$container[App\Action\Admin\SchedImport::class] = function ($c) use ($sr, $uploadPath) {

    return new \App\Action\Admin\SchedImport($c, $sr, $uploadPath);
};

$container[App\Action\Admin\SchedImportController::class] = function ($c) use ($sr, $uploadPath) {
    $v = new \App\Action\Admin\SchedImport($c, $sr, $uploadPath);

    return new \App\Action\Admin\SchedImportController($c, $v);
};

// -----------------------------------------------------------------------------
// LogExport class
// -----------------------------------------------------------------------------
$container[App\Action\Admin\LogExport::class] = function ($c) use ($sr) {

    return new \App\Action\Admin\LogExport($c, $sr);
};

$container[App\Action\Admin\LogExportController::class] = function ($c) use ($sr) {
    $v = new \App\Action\Admin\LogExport($c, $sr);

    return new \App\Action\Admin\LogExportController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedEnd class
// -----------------------------------------------------------------------------
$container[App\Action\End\SchedEndController::class] = function ($c) use ($sr) {

    return new \App\Action\End\SchedEndController($c);
};

// -----------------------------------------------------------------------------
// NoEventsView class
// -----------------------------------------------------------------------------
$container[App\Action\NoEvents\NoEventsView::class] = function ($c) use ($sr) {

    return new \App\Action\NoEvents\NoEventsView($c, $sr);
};

$container[App\Action\NoEvents\NoEventsController::class] = function ($c) use ($sr) {
    $v = new \App\Action\NoEvents\NoEventsView($c, $sr);

    return new \App\Action\NoEvents\NoEventsController($c, $v);
};

// -----------------------------------------------------------------------------
// EditGameView class
// -----------------------------------------------------------------------------
$container[App\Action\EditGame\EditGameView::class] = function ($c) use ($sr) {

    return new \App\Action\EditGame\EditGameView($c, $sr);
};

$container[App\Action\EditGame\EditGameController::class] = function ($c) use ($sr) {
    $v = new \App\Action\EditGame\EditGameView($c, $sr);

    return new \App\Action\EditGame\EditGameController($c, $v);
};

// -----------------------------------------------------------------------------
// FieldMapView class
// -----------------------------------------------------------------------------
$container[App\Action\PDF\ExportPDF::class] = function () {

    return new \App\Action\PDF\ExportPDF();
};

$container[App\Action\PDF\PDFController::class] = function ($c) {
    $v = new \App\Action\PDF\ExportPDF();

    return new \App\Action\PDF\PDFController($c, $v);
};

