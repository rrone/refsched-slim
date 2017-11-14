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
                PROJECT_ROOT . '/src/Action/SAR',
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
            'version' => '2017.11.13.00'
        ],

        'assignor' => [
            'name' => 'Jody Kinsey',
            'email' => 'jodykinsey23@gmail.com'
        ],

        'issueTracker' => 'https://github.com/rrone/refsched/issues'

    ],

    'settings.test' => false,

];

$config = include(PROJECT_ROOT . '/config/config.php');

return $settings;
