<?php
namespace Mopa\Bundle\RemoteUpdateBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CheckAndUpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
	        ->setName('mopa:update:checkandupdate')
	        ->setDescription('update local installation if there are new jobs')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $updateService = $this->getContainer()->get('mopa_local_update_service');
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        if($job = $em->getRepository("MopaRemoteUpdateBundle:UpdateJob")->getPendingJob()){
			$remote = $job->getRemote();
	        $output->writeln("Starting local update with conf for $remote ... ");
        	$updateService->doUpdate($job);
        	$output->writeln("Status: " . ($job->getStatusMessage()));
	        if( $output->getVerbosity() > 1){
	        	$output->writeln("Got from Remote $remote:");
	        	$output->writeln($job->getMessage());
	        }
        }
        else{
	        if( $output->getVerbosity() > 1){
	        	$output->writeln("Nothing to do.");
	        }
        }
    }
}