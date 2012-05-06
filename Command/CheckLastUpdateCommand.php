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
        $output->writeln("Checking for last remote update for $remote ... ");
        $updateService = $this->getContainer()->get('mopa_remote_update_service');
        $jobs = $updateService->check($remote, $count);
        if(count($jobs)){
	        foreach($jobs as $job){
	        	$output->writeln("Last Update was created at: ".$job->created_at);
	        	$output->writeln("Last Update was started at: ".$job->start_at);
	        	$output->writeln("Last Update was finished at: ".$job->finished_at);
	        	$output->writeln("Status: ".$job->success);

	        	if( $output->getVerbosity() > 1){
	        		$output->writeln("Message: ".$job->message);
	        	}
	        }
        }
        else{
        	$output->writeln("No finished jobs found");
        }
    }
}