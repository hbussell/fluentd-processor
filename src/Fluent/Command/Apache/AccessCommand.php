<?php

namespace Fluent\Command\Apache;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AccessCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('apache:access')
            ->setDescription('Process Apache access log')
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                'Access.log file'
            )
            ->addArgument(
               'application',
               InputOption::REQUIRED,
               'Application name to tag events with'
            )

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');
        $application = $input->getArgument('application');
        $output->writeln('process file ' . $file . ' - for ' .$application);
    }
}
