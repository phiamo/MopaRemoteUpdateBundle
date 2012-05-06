<?php
namespace Mopa\Bundle\RemoteUpdateBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RemoteUpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        ->setName('mopa:update:remote')
        ->setDescription('update remote installation')
        ->addArgument('remote', InputArgument::REQUIRED, 'Which remote to use?')
        //->addOption('environments', null, InputOption::VALUE_IS_ARRAY, 'Which environment to update? Default: dev')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $remote = $input->getArgument('remote');
        $api = $this->getContainer()->get('mopa_remote_update_service');
        $output->writeln("Starting remote update on $remote ... ");
        $response = $api->update($remote);
        if(isset($response->error)){
        		$output->writeln("Had an error: ". $response->error->message);

        }else{
	        if( $output->getVerbosity() > 1){
	        	$output->writeln("Got from Remote $remote:");
	        	foreach($response->message as $line){
	        		$output->writeln($line);
	        	}
	        }
	        $output->writeln("done.");
        }

    }
}