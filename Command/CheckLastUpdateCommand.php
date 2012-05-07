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
        	->addArgument('count', InputArgument::OPTIONAL, 'How many to show', 1)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $remote = $input->getArgument('remote');
        $count = $input->getArgument('count');
        $output->writeln("Checking for last" . ($count > 1 ? " ($count)" : "" ). " remote update" . ($count > 1 ? "s" : "" ). " for $remote ... ");
        try{
	        $updateService = $this->getContainer()->get('mopa_remote_update_service');
	        $jobs = $updateService->check($remote, $count);
	        foreach($jobs as $job){
	        	$output->writeln("Update was created at: ".$job->getCreatedAt()->format('Y-m-d H:i:s'));
	        	if($job->getFinishedAt()){
	        		$output->writeln("Update was started at: ".$job->getStartAt()->format('Y-m-d H:i:s'));
	        	}
	        	else{
	        		$output->writeln("Update isnt started yet ");
	        	}
	        	if($job->getFinishedAt()){
	        		$output->writeln("Update was finished at: ".$job->getFinishedAt()->format('Y-m-d H:i:s'));
	        	}
	        	else{
	        		$output->writeln("Update isnt finished yet ");
	        	}
	        	$output->writeln("Status: " . ($job->getStatusMessage()));
	        	if( $output->getVerbosity() > 1){
	        		$output->writeln("Message: " . $job->getMessage());
	        	}
	        }
	        if(0 === count($jobs)){
	        	$output->writeln("No Jobs found");
	        }
        }
        catch(RuntimeException $e){
        	$output->writeln("Had an error: ". $e->getMessage());
        }

    }
}