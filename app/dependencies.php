<?php
// DIC configuration
use App\Action\Admin\AdminController;
use App\Action\Admin\AdminView;
use App\Action\Admin\LogExport;
use App\Action\Admin\LogExportController;
use App\Action\Admin\SchedImport;
use App\Action\Admin\SchedImportController;
use App\Action\Admin\SchedTemplateExport;
use App\Action\Admin\SchedTemplateExportController;
use App\Action\EditGame\EditGameController;
use App\Action\EditGame\EditGameView;
use App\Action\EditEvents\EditEventsController;
use App\Action\EditEvents\EditEventsView;
use App\Action\EditRef\SchedEditRefController;
use App\Action\EditRef\SchedEditRefView;
use App\Action\End\SchedEndController;
use App\Action\Full\SchedExportController;
use App\Action\Full\SchedExportXl;
use App\Action\Full\SchedFullController;
use App\Action\Full\SchedFullView;
use App\Action\Greet\GreetView;
use App\Action\Greet\SchedGreetController;
use App\Action\InfoModal\InfoModalView;
use App\Action\Lock\SchedLockController;
use App\Action\Lock\SchedLockView;
use App\Action\Lock\SchedUnlockController;
use App\Action\Logon\LogonController;
use App\Action\Logon\LogonUsersController;
use App\Action\Logon\LogonView;
use App\Action\Master\SchedMasterController;
use App\Action\Master\SchedMasterView;
use App\Action\MedalRound\HideMedalRoundController;
use App\Action\MedalRound\HideMedalRoundDivisionsController;
use App\Action\MedalRound\MedalRoundDivisionsView;
use App\Action\MedalRound\MedalRoundView;
use App\Action\MedalRound\ShowMedalRoundController;
use App\Action\MedalRound\ShowMedalRoundDivisionsController;
use App\Action\NoEvents\NoEventsController;
use App\Action\NoEvents\NoEventsView;
use App\Action\PDF\ExportPDF;
use App\Action\PDF\PDFController;
use App\Action\Refs\SchedRefsController;
use App\Action\Refs\SchedRefsView;
use App\Action\SAR\SARAction;
use App\Action\Sched\SchedSchedController;
use App\Action\Sched\SchedSchedView;
use App\Action\SchedulerRepository;
use Slim\Container;
use TheIconic\NameParser\Parser;
use Twig\Extension\DebugExtension;

if(!isset($app)) {
    $app = null;
}

$container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Twig
/**
 * @param Container $c
 * @return Slim\Views\Twig
 */
$container['view'] = function (Container $c) {
    $settings = $c['settings'];
    $view = new Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);

    //Manage Twig base_url() returns port 80 when used over HTTPS connection
    $view['env_uri'] = $settings['env_uri'];
    $view['name'] = $settings['section']['name'];
    $view['title'] = $settings['section']['title'];
    $view['header'] = $settings['section']['header'];
    $view['assignoremail'] = $settings['assignor']['email'];
    $view['assignorrole'] = $settings['assignor']['role'];
    $view['issueTracker'] = $settings['issueTracker'];
    $view['banner'] = $settings['banner'];

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $c['request']->getUri()));
    $view->addExtension(new DebugExtension());

    $Version = new Twig\TwigFunction('version', function () use ($settings) {
        return 'Version ' . $settings['version']['version'];
    });

    $twig = $view->getEnvironment();

    $twig->addFunction($Version);

    return $view;
};

// Flash messages
$container['flash'] = function () {
    return new Slim\Flash\Messages;
};

unset($container['errorHandler']);
$container['errorHandler'] = function ($c) {
    if ($c['settings']['debug']) {
        return null;
    }

    return function ($exception) use ($c) {

    var_dump($exception);

        return $c['response']->withStatus(500)
                             ->withHeader('Content-Type', 'text/html')
                             ->write($exception->xdebug_message);
        //// 404.html, or 40x.html, or 4xx.html, or error.html
        //
        //$templates = array(
        //    'errors/'.$exception.'.html.twig',
        //    'errors/'.substr($exception, 0, 2).'x.html.twig',
        //    'errors/'.substr($exception, 0, 1).'xx.html.twig',
        //    'errors/default.html.twig',
        //);
        //
        //return new Response($container['view']->resolveTemplate($templates)->render(array('code' => $exception)), $exception);
    };
};


// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

// monolog
$container['logger'] = function (Container $c) {
    $settings = $c['settings'];
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

    $capsule->addConnection($c['settings']['dbConfig']);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

$container['sr'] = function (Container $c) {
    $db = $c['db'];

    return new SchedulerRepository($db);
};

$container['p'] = function () {
    return new Parser();
};

// -----------------------------------------------------------------------------
// Action dependency Injection
// -----------------------------------------------------------------------------
$db = $container['db'];

/* @var App\Action\SchedulerRepository $sr */
$sr = $container['sr'];

/** @var Parser $p */
$p = $container['p'];

$view = $container['view'];
$uploadPath = $container['settings']['upload_path'];

$container[App\Action\SchedulerRepository::class] = function ($db) {

    return new SchedulerRepository($db);
};

// -----------------------------------------------------------------------------
// Admin class
// -----------------------------------------------------------------------------
$container[App\Action\Admin\AdminView::class] = function ($c) use ($sr) {

    return new AdminView($c, $sr);
};

$container[App\Action\Admin\AdminController::class] = function ($c) use ($sr) {
    $v = new AdminView($c, $sr);

    return new AdminController($c, $v);
};

// -----------------------------------------------------------------------------
// Logon class
// -----------------------------------------------------------------------------
$container[App\Action\Logon\LogonView::class] = function ($c) use ($sr) {

    return new LogonView($c, $sr);
};

$container[App\Action\Logon\LogonController::class] = function ($c) use ($sr) {
    $v = new LogonView($c, $sr);

    return new LogonController($c, $v);
};

$container[App\Action\Logon\LogonUsersController::class] = function ($c) use ($sr) {
    $v = new LogonView($c, $sr);

    return new LogonUsersController($c, $v);
};

// -----------------------------------------------------------------------------
// Greet class
// -----------------------------------------------------------------------------
$container[App\Action\Greet\GreetView::class] = function ($c) use ($sr) {

    return new GreetView($c, $sr);
};

$container[App\Action\Greet\SchedGreetController::class] = function ($c) use ($sr) {
    $v = new GreetView($c, $sr);

    return new SchedGreetController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedFull class
// -----------------------------------------------------------------------------
$container[App\Action\Full\SchedFullView::class] = function ($c) use ($sr) {

    return new SchedFullView($c, $sr);
};

$container[App\Action\Full\SchedFullController::class] = function ($c) use ($sr) {
    $v = new SchedFullView($c, $sr);

    return new SchedFullController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedExport class
// -----------------------------------------------------------------------------
$container[App\Action\Full\SchedExportXl::class] = function () use ($sr) {

    return new SchedExportXl($sr);
};

$container[App\Action\Full\SchedExportController::class] = function ($c) use ($sr) {
    $v = new SchedExportXl($sr);

    return new SchedExportController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedSched class
// -----------------------------------------------------------------------------
$container[App\Action\Sched\SchedSchedView::class] = function ($c) use ($sr) {

    return new SchedSchedView($c, $sr);
};

$container[App\Action\Sched\SchedSchedController::class] = function ($c) use ($sr) {
    $v = new SchedSchedView($c, $sr);

    return new SchedSchedController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedMaster class
// -----------------------------------------------------------------------------
$container[App\Action\Master\SchedMasterView::class] = function ($c) use ($sr) {

    return new SchedMasterView($c, $sr);
};

$container[App\Action\Master\SchedMasterController::class] = function ($c) use ($sr) {
    $v = new SchedMasterView($c, $sr);

    return new SchedMasterController($c, $v);
};

// -----------------------------------------------------------------------------
// Lock & Unlock classes
// -----------------------------------------------------------------------------
$container[App\Action\Lock\SchedLockView::class] = function ($c) use ($sr) {

    return new SchedLockView($c, $sr);
};

$container[App\Action\Lock\SchedLockController::class] = function ($c) use ($sr) {
    $v = new SchedLockView($c, $sr);

    return new SchedLockController($c, $v);
};

$container[App\Action\Lock\SchedUnlockController::class] = function ($c) use ($sr) {
    $v = new SchedLockView($c, $sr);

    return new SchedUnlockController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedRefs class
// -----------------------------------------------------------------------------
$container[App\Action\Refs\SchedRefsView::class] = function ($c) use ($sr) {

    return new SchedRefsView($c, $sr);
};

$container[App\Action\Refs\SchedRefsController::class] = function ($c) use ($sr) {
    $v = new SchedRefsView($c, $sr);

    return new SchedRefsController($c, $v);
};

// -----------------------------------------------------------------------------
// EditRef class
// -----------------------------------------------------------------------------
$container[App\Action\EditRef\SchedEditRefView::class] = function ($c) use ($sr, $p) {

    return new SchedEditRefView($c, $sr, $p);
};

$container[App\Action\EditRef\SchedEditRefController::class] = function ($c) use ($sr, $p) {
    $v = new SchedEditRefView($c, $sr, $p);

    return new SchedEditRefController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedTemplateExport class
// -----------------------------------------------------------------------------
$container[App\Action\Admin\SchedTemplateExport::class] = function ($c) use ($sr) {

    return new SchedTemplateExport($c, $sr);
};

$container[App\Action\Admin\SchedTemplateExportController::class] = function ($c) use ($sr) {
    $v = new SchedTemplateExport($c, $sr);

    return new SchedTemplateExportController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedImport class
// -----------------------------------------------------------------------------
$container[App\Action\Admin\SchedImport::class] = function ($c) use ($sr, $uploadPath) {

    return new SchedImport($c, $sr, $uploadPath);
};

$container[App\Action\Admin\SchedImportController::class] = function ($c) use ($sr, $uploadPath) {
    $v = new SchedImport($c, $sr, $uploadPath);

    return new SchedImportController($c, $v);
};

// -----------------------------------------------------------------------------
// LogExport class
// -----------------------------------------------------------------------------
$container[App\Action\Admin\LogExport::class] = function ($c) use ($sr) {

    return new LogExport($c, $sr);
};

$container[App\Action\Admin\LogExportController::class] = function ($c) use ($sr) {
    $v = new LogExport($c, $sr);

    return new LogExportController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedEnd class
// -----------------------------------------------------------------------------
$container[App\Action\End\SchedEndController::class] = function ($c) {

    return new SchedEndController($c);
};

// -----------------------------------------------------------------------------
// NoEventsView class
// -----------------------------------------------------------------------------
$container[App\Action\NoEvents\NoEventsView::class] = function ($c) use ($sr) {

    return new NoEventsView($c, $sr);
};

$container[App\Action\NoEvents\NoEventsController::class] = function ($c) use ($sr) {
    $v = new NoEventsView($c, $sr);

    return new NoEventsController($c, $v);
};

// -----------------------------------------------------------------------------
// EditGameView class
// -----------------------------------------------------------------------------
$container[App\Action\EditGame\EditGameView::class] = function ($c) use ($sr) {

    return new EditGameView($c, $sr);
};

$container[App\Action\EditGame\EditGameController::class] = function ($c) use ($sr) {
    $v = new EditGameView($c, $sr);

    return new EditGameController($c, $v);
};

// -----------------------------------------------------------------------------
// EditEventsView class
// -----------------------------------------------------------------------------
$container[App\Action\EditEvents\EditEventsView::class] = function ($c) use ($sr) {

    return new EditEventsView($c, $sr);
};

$container[App\Action\EditEvents\EditEventsController::class] = function ($c) use ($sr) {
    $v = new EditEventsView($c, $sr);

    return new EditEventsController($c, $v);
};

// -----------------------------------------------------------------------------
// FieldMapView class
// -----------------------------------------------------------------------------
$container[App\Action\PDF\ExportPDF::class] = function () {

    return new ExportPDF();
};

$container[App\Action\PDF\PDFController::class] = function ($c) {
    $v = new ExportPDF();

    return new PDFController($c, $v);
};

// -----------------------------------------------------------------------------
// MedalRound classes
// -----------------------------------------------------------------------------
$container[App\Action\MedalRound\MedalRoundView::class] = function ($c) use ($sr) {

    return new MedalRoundView($c, $sr);
};

$container[App\Action\MedalRound\ShowMedalRoundController::class] = function ($c) use ($sr) {
    $v = new MedalRoundView($c, $sr);

    return new ShowMedalRoundController($c, $v);
};

$container[App\Action\MedalRound\HideMedalRoundController::class] = function ($c) use ($sr) {
    $v = new MedalRoundView($c, $sr);

    return new HideMedalRoundController($c, $v);
};

$container[App\Action\MedalRound\ShowMedalRoundDivisionsController::class] = function ($c) use ($sr) {
    $v = new MedalRoundDivisionsView($c, $sr);

    return new ShowMedalRoundDivisionsController($c, $v);
};

$container[App\Action\MedalRound\HideMedalRoundDivisionsController::class] = function ($c) use ($sr) {
    $v = new MedalRoundDivisionsView($c, $sr);

    return new HideMedalRoundDivisionsController($c, $v);
};

// -----------------------------------------------------------------------------
// SAR Function class
// -----------------------------------------------------------------------------
$container[App\Action\SAR\SARAction::class] = function () use ($sr) {

    return new SARAction($sr);
};

// -----------------------------------------------------------------------------
// InfoModal class
// -----------------------------------------------------------------------------
$container[App\Action\InfoModal\InfoModalController::class] = function ($c) use ($sr) {
    $v = new InfoModalView($c, $sr);

    return new App\Action\InfoModal\InfoModalController($c, $v);
};
