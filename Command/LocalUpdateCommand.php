<?php
namespace Mopa\Bundle\RemoteUpdateBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class LocalUpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
	        ->setName('mopa:update:local')
	        ->setDescription('update local installation')
	        ->addArgument('remote', InputArgument::REQUIRED, 'Which remote config to use?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $remote = $input->getArgument('remote');

        $output->writeln("Starting local update with conf for $remote ... ");
        $job = $this->getContainer()
        				->get('mopa_local_update_service')
        				->update($remote, "local");
        $output->writeln("Status: " . $job->getStatusMessage());
        if( $output->getVerbosity() > 1){
        	$output->writeln("Got from Remote $remote:");
        	$output->writeln($job->getMessage());
        }
        $output->writeln("done.");

    }
}