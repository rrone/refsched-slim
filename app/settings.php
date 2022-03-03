<?php
return [
    // Test Settings
    'settings.test' => false,

    'settings' => [
        // App Settings
        'version' => [
            'version' => '2022.03.02.10'
        ],

        'assignor' => [
            'name' => 'Jody Kinsey',
            'role' => 'Section 1 Referee Assignor',
            'email' => 'jodykinsey23@gmail.com'
        ],

        'section' => [
            'name' => 'Section 1',
            'title' => 'S1: Referee Schedule',
            'header' => 'Section 1 Event Schedule',
            'icon' => '/images/s1logo_rs.png'
        ],

        'issueTracker' => 'https://github.com/rrone/refsched/issues?q=is%3Aissue+project%3Arrone%2Frefsched%2F1',

        // Slim Settings
        'determineRouteBeforeAppMiddleware' => false,

        'displayErrorDetails'  => true,

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

    ],

];
