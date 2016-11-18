<?php

use /** @noinspection PhpUndefinedNamespaceInspection */
    Symfony\Component\Console\Application;
use /** @noinspection PhpUndefinedNamespaceInspection */
    Symfony\Component\Console\Input\InputInterface;
use /** @noinspection PhpUndefinedNamespaceInspection */
    Symfony\Component\Console\Output\OutputInterface;
use /** @noinspection PhpUndefinedNamespaceInspection */
    Symfony\Component\Console\Input\InputArgument;
use /** @noinspection PhpUndefinedNamespaceInspection */
    Symfony\Component\Console\Input\InputOption;

$console = new Application('My Silex Application', 'n/a');
$console->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'));
$console->setDispatcher($app['dispatcher']);
$console
    ->register('my-command')
    ->setDefinition(array(
        // new InputOption('some-option', null, InputOption::VALUE_NONE, 'Some help'),
    ))
    ->setDescription('My command description')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        // do something
    })
;

return $console;
