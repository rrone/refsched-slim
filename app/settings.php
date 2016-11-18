<?php
$settings = [
    'settings' => [
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => false,

        // View settings
        'view' => [
            'template_path' => [
                __DIR__ . '/../templates',
                __DIR__ . '/../src/Action/EditRef',
                __DIR__ . '/../src/Action/End',
                __DIR__ . '/../src/Action/Full',
                __DIR__ . '/../src/Action/Greet',
                __DIR__ . '/../src/Action/Lock',
                __DIR__ . '/../src/Action/Logon',
                __DIR__ . '/../src/Action/Master',
                __DIR__ . '/../src/Action/Refs',
                __DIR__ . '/../src/Action/Sched',
                __DIR__ . '/../src/Action/Admin',
            ],
            'twig' => [
                'cache' => __DIR__ . '/../var/cache/twig',
                'debug' => true,
                'auto_reload' => true,
            ],
        ],

        'upload_path' => __DIR__ . '/../var/uploads/',
        
        // monolog settings
        'logger' => [
            'name' => 'app',
            'path' => __DIR__ . '/../var/logs/app.log',
        ],

        'version' => [
            'version' => '2016.11.14.06'
        ]
    ],

    'settings.test' => false,

];

$local = include(__DIR__ . '/../config/local.php');
$settings['settings']['db'] = $local['db'];

$settings['test']['user'] = $local['user_test'];

$settings['test']['admin'] = $local['admin_test'];

return $settings;
