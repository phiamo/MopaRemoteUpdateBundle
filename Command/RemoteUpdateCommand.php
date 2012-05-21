<?php
namespace Mopa\Bundle\RemoteUpdateBundle\Command;

use Mopa\Bundle\RemoteUpdateBundle\Model\Exceptions\HavingJobPendingException;

use Mopa\Bundle\RemoteUpdateBundle\Entity\UpdateJob;

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
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $remote = $input->getArgument('remote');
        $output->writeln("Starting remote update on $remote ... ");
        try{
            $job = $this->getContainer()
                        ->get('mopa_remote_update_service')
                        ->update($remote);
            $output->writeln("Status: " . ($job->getStatusMessage()));
            if ( $output->getVerbosity() > 1) {
                $output->writeln("Got from Remote $remote:");
                $output->writeln($job->getMessage());
            }
        }
        catch(HavingJobPendingException $e) {
            $output->writeln("<comment>" . $e->getMessage() . "</comment>");
        }

    }
}