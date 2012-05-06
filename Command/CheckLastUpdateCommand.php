<?php
namespace Mopa\Bundle\RemoteUpdateBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CheckLastUpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
	        ->setName('mopa:update:check')
	        ->setDescription('update local installation if there are new jobs')
        	->addArgument('remote', InputArgument::REQUIRED, 'Which remote to use?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $remote = $input->getArgument('remote');
        $output->writeln("Checking for last remote update for $remote ... ");
        $updateService = $this->getContainer()->get('mopa_remote_update_service');
        $job = $updateService->check($remote);
        if($job !== null){
        	$output->writeln("Last Update was created at: ".$job['createdAt']);
        	$output->writeln("Last Update was started at: ".$job['startAt']);
        	$output->writeln("Last Update was finished at: ".$job['finishedAt']);
        	$output->writeln("Status: ".$job['status']);
        	$output->writeln("Message: ".$job['message']);
        }
        else{
        	$output->writeln("No finished jobs found");
        }
    }
}