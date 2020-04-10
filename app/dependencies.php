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
use App\Action\EditRef\SchedEditRefController;
use App\Action\EditRef\SchedEditRefView;
use App\Action\End\SchedEndController;
use App\Action\Full\SchedExportController;
use App\Action\Full\SchedExportXl;
use App\Action\Full\SchedFullController;
use App\Action\Full\SchedFullView;
use App\Action\Greet\GreetView;
use App\Action\Greet\SchedGreetController;
use App\Action\InfoModal\InfoModalController;
use App\Action\InfoModal\InfoModalView;
use App\Action\Lock\SchedLockController;
use App\Action\Lock\SchedLockView;
use App\Action\Lock\SchedUnlockController;
use App\Action\Logon\LogonController;
use App\Action\Logon\LogonUsersController;
use App\Action\Logon\LogonView;
use App\Action\Master\SchedMasterController;
use App\Action\Master\SchedMasterView;
use App\Action\MedalRound\HideMedalRoundAssignmentsController;
use App\Action\MedalRound\HideMedalRoundController;
use App\Action\MedalRound\HideMedalRoundDivisionsController;
use App\Action\MedalRound\MedalRoundAssignmentsView;
use App\Action\MedalRound\MedalRoundDivisionsView;
use App\Action\MedalRound\MedalRoundView;
use App\Action\MedalRound\ShowMedalRoundAssignmentsController;
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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Container;
use Slim\Http\Response;
use Twig\Extension\DebugExtension;

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
    $view['icon'] = $settings['section']['icon'];
    $view['assignoremail'] = $settings['assignor']['email'];
    $view['assignorrole'] = $settings['assignor']['role'];
    $view['issueTracker'] = $settings['issueTracker'];
    $view['banner'] = $settings['banner'];

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $c['request']->getUri()));
    $view->addExtension(new DebugExtension());

    $Version = new Twig\TwigFunction(
        'version', function () use ($settings) {
        return 'Version '.$settings['version']['version'];
    }
    );

    $twig = $view->getEnvironment();

    $twig->addFunction($Version);

    return $view;
};

// Flash messages
$container['flash'] = function () {
    return new Slim\Flash\Messages;
};

//unset($container['errorHandler']);
$container['errorHandler'] = function ($c) {
//    if ($c['settings']['debug']) {
//        return;
//    }

    return function ($request, $response, $exception) use ($c) {

        var_dump($exception);

        return $c['response']->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write($exception->xdebug_message);
        // 404.html, or 40x.html, or 4xx.html, or error.html

        $templates = array(
            'errors/'.$exception.'.html.twig',
            'errors/'.substr($exception, 0, 2).'x.html.twig',
            'errors/'.substr($exception, 0, 1).'xx.html.twig',
            'errors/default.html.twig',
        );

        return new Response(
            $container['view']->resolveTemplate($templates)->render(array('code' => $exception)),
            $exception
        );
    };
};

unset($container['notFoundHandler']);
$container['notFoundHandler'] = function ($c) {
    return function (ServerRequestInterface $request, ResponseInterface $response) use ($c) {
        return $response->withRedirect($c['router']->pathFor(''));
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
    return new FullNameParser();
};

// -----------------------------------------------------------------------------
// Action dependency Injection
// -----------------------------------------------------------------------------
$db = $container['db'];

/* @var SchedulerRepository $sr */
$sr = $container['sr'];

/** @var FullNameParser $p */
$p = $container['p'];

$view = $container['view'];
$uploadPath = $container['settings']['upload_path'];

$container[SchedulerRepository::class] = function ($db) {

    return new SchedulerRepository($db);
};

// -----------------------------------------------------------------------------
// Admin class
// -----------------------------------------------------------------------------
$container[AdminView::class] = function ($c) use ($sr) {

    return new AdminView($c, $sr);
};

$container[AdminController::class] = function ($c) use ($sr) {
    $v = new AdminView($c, $sr);

    return new AdminController($c, $v);
};

// -----------------------------------------------------------------------------
// Logon class
// -----------------------------------------------------------------------------
$container[LogonView::class] = function ($c) use ($sr) {

    return new LogonView($c, $sr);
};

$container[LogonController::class] = function ($c) use ($sr) {
    $v = new LogonView($c, $sr);

    return new LogonController($c, $v);
};

$container[LogonUsersController::class] = function ($c) use ($sr) {
    $v = new LogonView($c, $sr);

    return new LogonUsersController($c, $v);
};

// -----------------------------------------------------------------------------
// Greet class
// -----------------------------------------------------------------------------
$container[GreetView::class] = function ($c) use ($sr) {

    return new GreetView($c, $sr);
};

$container[SchedGreetController::class] = function ($c) use ($sr) {
    $v = new GreetView($c, $sr);

    return new SchedGreetController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedFull class
// -----------------------------------------------------------------------------
$container[SchedFullView::class] = function ($c) use ($sr) {

    return new SchedFullView($c, $sr);
};

$container[SchedFullController::class] = function ($c) use ($sr) {
    $v = new SchedFullView($c, $sr);

    return new SchedFullController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedExport class
// -----------------------------------------------------------------------------
$container[SchedExportXl::class] = function () use ($sr) {

    return new SchedExportXl($sr);
};

$container[SchedExportController::class] = function ($c) use ($sr) {
    $v = new SchedExportXl($sr);

    return new SchedExportController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedSched class
// -----------------------------------------------------------------------------
$container[SchedSchedView::class] = function ($c) use ($sr) {

    return new SchedSchedView($c, $sr);
};

$container[SchedSchedController::class] = function ($c) use ($sr) {
    $v = new SchedSchedView($c, $sr);

    return new SchedSchedController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedMaster class
// -----------------------------------------------------------------------------
$container[SchedMasterView::class] = function ($c) use ($sr) {

    return new SchedMasterView($c, $sr);
};

$container[SchedMasterController::class] = function ($c) use ($sr) {
    $v = new SchedMasterView($c, $sr);

    return new SchedMasterController($c, $v);
};

// -----------------------------------------------------------------------------
// Lock & Unlock classes
// -----------------------------------------------------------------------------
$container[SchedLockView::class] = function ($c) use ($sr) {

    return new SchedLockView($c, $sr);
};

$container[SchedLockController::class] = function ($c) use ($sr) {
    $v = new SchedLockView($c, $sr);

    return new SchedLockController($c, $v);
};

$container[SchedUnlockController::class] = function ($c) use ($sr) {
    $v = new SchedLockView($c, $sr);

    return new SchedUnlockController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedRefs class
// -----------------------------------------------------------------------------
$container[SchedRefsView::class] = function ($c) use ($sr) {

    return new SchedRefsView($c, $sr);
};

$container[SchedRefsController::class] = function ($c) use ($sr) {
    $v = new SchedRefsView($c, $sr);

    return new SchedRefsController($c, $v);
};

// -----------------------------------------------------------------------------
// EditRef class
// -----------------------------------------------------------------------------
$container[SchedEditRefView::class] = function ($c) use ($sr, $p) {

    return new SchedEditRefView($c, $sr, $p);
};

$container[SchedEditRefController::class] = function ($c) use ($sr, $p) {
    $v = new SchedEditRefView($c, $sr, $p);

    return new SchedEditRefController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedTemplateExport class
// -----------------------------------------------------------------------------
$container[SchedTemplateExport::class] = function ($c) use ($sr) {

    return new SchedTemplateExport($c, $sr);
};

$container[SchedTemplateExportController::class] = function ($c) use ($sr) {
    $v = new SchedTemplateExport($c, $sr);

    return new SchedTemplateExportController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedImport class
// -----------------------------------------------------------------------------
$container[SchedImport::class] = function ($c) use ($sr, $uploadPath) {

    return new SchedImport($c, $sr, $uploadPath);
};

$container[SchedImportController::class] = function ($c) use ($sr, $uploadPath) {
    $v = new SchedImport($c, $sr, $uploadPath);

    return new SchedImportController($c, $v);
};

// -----------------------------------------------------------------------------
// LogExport class
// -----------------------------------------------------------------------------
$container[LogExport::class] = function ($c) use ($sr) {

    return new LogExport($c, $sr);
};

$container[LogExportController::class] = function ($c) use ($sr) {
    $v = new LogExport($c, $sr);

    return new LogExportController($c, $v);
};

// -----------------------------------------------------------------------------
// SchedEnd class
// -----------------------------------------------------------------------------
$container[SchedEndController::class] = function ($c) {

    return new SchedEndController($c);
};

// -----------------------------------------------------------------------------
// NoEventsView class
// -----------------------------------------------------------------------------
$container[NoEventsView::class] = function ($c) use ($sr) {

    return new NoEventsView($c, $sr);
};

$container[NoEventsController::class] = function ($c) use ($sr) {
    $v = new NoEventsView($c, $sr);

    return new NoEventsController($c, $v);
};

// -----------------------------------------------------------------------------
// EditGameView class
// -----------------------------------------------------------------------------
$container[EditGameView::class] = function ($c) use ($sr) {

    return new EditGameView($c, $sr);
};

$container[EditGameController::class] = function ($c) use ($sr) {
    $v = new EditGameView($c, $sr);

    return new EditGameController($c, $v);
};

// -----------------------------------------------------------------------------
// FieldMapView class
// -----------------------------------------------------------------------------
$container[ExportPDF::class] = function () {

    return new ExportPDF();
};

$container[PDFController::class] = function ($c) {
    $v = new ExportPDF();

    return new PDFController($c, $v);
};

// -----------------------------------------------------------------------------
// MedalRound classes
// -----------------------------------------------------------------------------
$container[MedalRoundView::class] = function ($c) use ($sr) {

    return new MedalRoundView($c, $sr);
};

$container[ShowMedalRoundController::class] = function ($c) use ($sr) {
    $v = new MedalRoundView($c, $sr);

    return new ShowMedalRoundController($c, $v);
};

$container[HideMedalRoundController::class] = function ($c) use ($sr) {
    $v = new MedalRoundView($c, $sr);

    return new HideMedalRoundController($c, $v);
};

$container[ShowMedalRoundDivisionsController::class] = function ($c) use ($sr) {
    $v = new MedalRoundDivisionsView($c, $sr);

    return new ShowMedalRoundDivisionsController($c, $v);
};

$container[HideMedalRoundDivisionsController::class] = function ($c) use ($sr) {
    $v = new MedalRoundDivisionsView($c, $sr);

    return new HideMedalRoundDivisionsController($c, $v);
};

$container[ShowMedalRoundAssignmentsController::class] = function ($c) use ($sr) {
    $v = new MedalRoundAssignmentsView($c, $sr);

    return new ShowMedalRoundAssignmentsController($c, $v);
};

$container[HideMedalRoundAssignmentsController::class] = function ($c) use ($sr) {
    $v = new MedalRoundAssignmentsView($c, $sr);

    return new HideMedalRoundAssignmentsController($c, $v);

};

// -----------------------------------------------------------------------------
// SAR Function class
// -----------------------------------------------------------------------------
$container[SARAction::class] = function () use ($sr) {

    return new SARAction($sr);
};

// -----------------------------------------------------------------------------
// InfoModal class
// -----------------------------------------------------------------------------
$container[InfoModalController::class] = function ($c) use ($sr) {
    $v = new InfoModalView($c, $sr);

    return new InfoModalController($c, $v);
};
