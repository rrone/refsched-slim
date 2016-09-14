<?php
// Routes

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', App\Action\Logon\LogonController::class)
    ->setName('logon');

$app->map(['GET', 'POST'], '/addref', App\Action\AddRef\SchedAddRefController::class)
    ->setName('addref');

$app->map(['GET', 'POST'], '/assign', App\Action\Assign\SchedAssignController::class)
    ->setName('assign');

$app->map(['GET', 'POST'], '/control', App\Action\Control\SchedControlController::class)
    ->setName('control');

$app->map(['GET', 'POST'], '/editref', App\Action\EditRef\SchedEditRefController::class)
    ->setName('editref');

$app->map(['GET', 'POST'], '/end', App\Action\End\SchedEndController::class)
    ->setName('end');

$app->map(['GET', 'POST'], '/full', App\Action\Full\SchedFullController::class)
    ->setName('full');

$app->map(['GET', 'POST'], '/greet', App\Action\Greet\SchedGreetController::class)
    ->setName('greet');

$app->map(['GET', 'POST'], '/lock', App\Action\Greet\SchedLockController::class)
    ->setName('lock');

$app->get('/logon', function ($req, $resp, $args) {
    $resp->withRedirect('/');
});

$app->map(['GET', 'POST'], '/unlock', App\Action\Greet\SchedLockController::class)
    ->setName('unlock');

$app->map(['GET', 'POST'], '/refs', App\Action\Greet\SchedRefsController::class)
    ->setName('refs');

$app->map(['GET', 'POST'], '/master', App\Action\Greet\SchedMasterController::class)
    ->setName('master');

$app->map(['GET', 'POST'], '/sched', App\Action\Greet\SchedSchedController::class)
    ->setName('sched');
