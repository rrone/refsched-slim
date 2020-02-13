<?php
// Routes

$container = $app->getContainer();

//Request::setTrustedProxies(array('127.0.0.1'));

$app->map(['GET', 'POST'], '/', App\Action\Logon\LogonController::class)
    ->setName('logon');
$container['logonPath'] = $container->get('router')->pathFor('logon');

$app->map(['GET', 'POST'], '/logon', App\Action\Logon\LogonController::class);
$app->map(['GET', 'POST'], '/logon/users', App\Action\Logon\LogonUsersController::class)
    ->setName('logonUsers');
$container['logonUsersPath'] = $container->get('router')->pathFor('logonUsers');

$app->map(['GET', 'POST'], '/editref', App\Action\EditRef\SchedEditRefController::class)
    ->setName('editref');
$container['editrefPath'] = $container->get('router')->pathFor('editref');

$app->map(['GET', 'POST'], '/end', App\Action\End\SchedEndController::class)
    ->setName('end');
$container['endPath'] = $container->get('router')->pathFor('end');

$app->map(['GET', 'POST'], '/full', App\Action\Full\SchedFullController::class)
    ->setName('full');
$container['fullPath'] = $container->get('router')->pathFor('full');

$app->map(['GET', 'POST'], '/greet', App\Action\Greet\SchedGreetController::class)
    ->setName('greet');
$container['greetPath'] = $container->get('router')->pathFor('greet');

$app->map(['GET', 'POST'], '/lock', App\Action\Lock\SchedLockController::class)
    ->setName('lock');
$container['lockPath'] = $container->get('router')->pathFor('lock');

$app->map(['GET', 'POST'], '/unlock', App\Action\Lock\SchedUnlockController::class)
    ->setName('unlock');
$container['unlockPath'] = $container->get('router')->pathFor('unlock');

$app->map(['GET', 'POST'], '/refs', App\Action\Refs\SchedRefsController::class)
    ->setName('refs');
$container['refsPath'] = $container->get('router')->pathFor('refs');

$app->map(['GET', 'POST'], '/master', App\Action\Master\SchedMasterController::class)
    ->setName('master');
$container['masterPath'] = $container->get('router')->pathFor('master');

$app->map(['GET', 'POST'], '/sched', App\Action\Sched\SchedSchedController::class)
    ->setName('sched');
$container['schedPath'] = $container->get('router')->pathFor('sched');

$app->map(['GET', 'POST'], '/fullexport', App\Action\Full\SchedExportController::class)
    ->setName('fullexport');
$container['fullXlsPath'] = $container->get('router')->pathFor('fullexport');

$app->map(['GET', 'POST'], '/adm', App\Action\Admin\AdminController::class)
    ->setName('admin');
$container['adminPath'] = $container->get('router')->pathFor('admin');

$app->map(['GET', 'POST'], '/adm/template', App\Action\Admin\SchedTemplateExportController::class)
    ->setName('sched_template');
$container['schedTemplatePath'] = $container->get('router')->pathFor('sched_template');

$app->map(['GET', 'POST'], '/adm/import', App\Action\Admin\SchedImportController::class)
    ->setName('sched_import');
$container['schedImportPath'] = $container->get('router')->pathFor('sched_import');

$app->get('/adm/log', App\Action\Admin\LogExportController::class)
    ->setName('log_export');
$container['logExportPath'] = $container->get('router')->pathFor('log_export');

$app->get('/na', App\Action\NoEvents\NoEventsController::class)
    ->setName('na');
$container['naPath'] = $container->get('router')->pathFor('na');

$app->map(['GET', 'POST'], '/editgame', App\Action\EditGame\EditGameController::class)
    ->setName('edit_game');
$container['editGamePath'] = $container->get('router')->pathFor('edit_game');

$app->map(['GET', 'POST'], '/editevents', App\Action\EditEvents\EditEventsController::class)
    ->setName('edit_events');
$container['editEventsPath'] = $container->get('router')->pathFor('edit_events');

$app->get('/fieldmap', App\Action\PDF\PDFController::class)
    ->setName('fieldmap');
$container['fieldmap'] = $container->get('router')->pathFor('fieldmap');

$app->map(['GET', 'POST'], '/hidemr', App\Action\MedalRound\HideMedalRoundController::class)
    ->setName('hidemr');
$container['hideMRPath'] = $container->get('router')->pathFor('hidemr');

$app->map(['GET', 'POST'], '/showmr', App\Action\MedalRound\ShowMedalRoundController::class)
    ->setName('showmr');
$container['showMRPath'] = $container->get('router')->pathFor('showmr');

$app->map(['GET', 'POST'], '/hidemrd', App\Action\MedalRound\HideMedalRoundDivisionsController::class)
    ->setName('hidemrd');
$container['hideMRDivPath'] = $container->get('router')->pathFor('hidemrd');

$app->map(['GET', 'POST'], '/showmrd', App\Action\MedalRound\ShowMedalRoundDivisionsController::class)
    ->setName('showmrd');
$container['showMRDivPath'] = $container->get('router')->pathFor('showmrd');

$app->map(['GET'], '/sar', App\Action\SAR\SARAction::class)
    ->setName('sar');

$app->map(['GET'], '/sar/{portal}', App\Action\SAR\SARAction::class)
    ->setName('portal');

$app->map(['GET'], '/info', App\Action\InfoModal\InfoModalController::class)
    ->setName('info');
$container['infoPath'] = $container->get('router')->pathFor('info');

$app->map(['GET'], '/info/{id}', App\Action\InfoModal\InfoModalController::class)
    ->setName('infoId');
