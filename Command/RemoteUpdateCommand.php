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
        ->setName('mopa:remote:update')
        ->setDescription('update remote installation')
        ->addArgument('remote', InputArgument::REQUIRED, 'Which remote to use?')
        //->addOption('environments', null, InputOption::VALUE_IS_ARRAY, 'Which environment to update? Default: dev')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('remote');
        $api = $this->getContainer()->get('mopa_remote_update_service');
        $api->setTarget($name)
            ->update();

        $output->writeln($name);

    }
}