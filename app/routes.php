<?php
// Routes

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->map(['GET', 'POST'], '/', App\Action\Logon\LogonDBController::class)
    ->setName('logon');

$app->map(['GET', 'POST'], '/logon', App\Action\Logon\LogonDBController::class);

$app->map(['GET', 'POST'], '/editref', App\Action\EditRef\SchedEditRefDBController::class)
    ->setName('editref');

$app->map(['GET', 'POST'], '/end', App\Action\End\SchedEndController::class)
    ->setName('end');

$app->map(['GET', 'POST'], '/full', App\Action\Full\SchedFullDBController::class)
    ->setName('full');

$app->map(['GET', 'POST'], '/greet', App\Action\Greet\SchedGreetDBController::class)
    ->setName('greet');

$app->map(['GET', 'POST'], '/lock', App\Action\Lock\SchedLockDBController::class)
    ->setName('lock');

$app->map(['GET', 'POST'], '/refs', App\Action\Refs\SchedRefsDBController::class)
    ->setName('refs');

$app->map(['GET', 'POST'], '/master', App\Action\Master\SchedMasterDBController::class)
    ->setName('master');

$app->map(['GET', 'POST'], '/sched', App\Action\Sched\SchedSchedDBController::class)
    ->setName('sched');

$app->map(['GET', 'POST'], '/unlock', App\Action\Lock\SchedUnlockDBController::class)
    ->setName('unlock');
    
$app->map(['GET', 'POST'], '/fullexport', App\Action\Full\SchedExportController::class)
    ->setName('fullexport');
    
$app->map(['GET', 'POST'], '/adm', App\Action\Admin\AdminController::class)
    ->setName('admin');

$app->map(['GET', 'POST'], '/adm/template', App\Action\Admin\SchedTemplateExportController::class)
    ->setName('sched_template');

$app->map(['GET', 'POST'], '/adm/import', App\Action\Admin\SchedImportController::class)
    ->setName('sched_import');

$app->get('/adm/log', App\Action\Admin\LogDumpController::class)
    ->setName('access_log');
