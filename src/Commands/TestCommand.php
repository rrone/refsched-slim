<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('command:test:a')
            ->setDescription('Command test')
            ->setHelp('This command tests the Slim3 bin/console function.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        echo sprintf("Verifying command ...\n");

        echo sprintf("...verified\n");
    }
}
