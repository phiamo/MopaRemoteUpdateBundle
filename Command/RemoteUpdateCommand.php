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
        ->addOption('environments', null, InputOption::VALUE_OPTIONAL, 'Which environment to update? Default: dev')
        ->addOption('remote', 'r', InputOption::VALUE_NONE, 'If not set will do this on this local installation')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('remote');
        $config = $this->getContainer()->getParameter('mopa_remote_update.remotes.' . $name);
        $api = $this->getContainer()->get('mopa_remote_update.api');
        $api->setTarget($name)
            ->;

        $output->writeln($name);
        var_dump($config);

    }
}