<?php
$settings = [
    'settings' => [
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => false,

        // View settings
        'view' => [
            'template_path' => [
                PROJECT_ROOT . '/templates',
                PROJECT_ROOT . '/src/Action/EditRef',
                PROJECT_ROOT . '/src/Action/End',
                PROJECT_ROOT . '/src/Action/Full',
                PROJECT_ROOT . '/src/Action/Greet',
                PROJECT_ROOT . '/src/Action/Lock',
                PROJECT_ROOT . '/src/Action/Logon',
                PROJECT_ROOT . '/src/Action/Master',
                PROJECT_ROOT . '/src/Action/Refs',
                PROJECT_ROOT . '/src/Action/Sched',
                PROJECT_ROOT . '/src/Action/Admin',
                PROJECT_ROOT . '/src/Action/NoEvents',
                PROJECT_ROOT . '/src/Action/EditGame',
                PROJECT_ROOT . '/src/Action/MedalRound',
            ],
            'twig' => [
                'cache' => PROJECT_ROOT . '/var/cache/twig',
                'debug' => true,
                'auto_reload' => true,
            ],
        ],

        'upload_path' => PROJECT_ROOT . '/var/uploads/',
        
        // monolog settings
        'logger' => [
            'name' => 'app',
            'path' => PROJECT_ROOT . '/var/logs/app.log',
        ],

        'version' => [
            'version' => '2017.01.04.x'
        ]
    ],

    'settings.test' => false,

];

$local = include(PROJECT_ROOT . '/config/local.php');

$settings['settings']['db'] = $local['db'];

$settings['test']['user'] = $local['user_test'];

$settings['test']['admin'] = $local['admin_test'];

$settings['test']['empty'] = $local['empty_test'];

$settings['test']['dev'] = $local['dev_test'];

return $settings;
