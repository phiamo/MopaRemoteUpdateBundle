<?php
namespace Mopa\Bundle\RemoteUpdateBundle\Model;

use Mopa\Bridge\Composer\Adapter\ComposerAdapter;
use Mopa\Bundle\RemoteUpdateBundle\Entity\UpdateJob;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\Process\Process;
use FOS\RestBundle\View\View;

class LocalUpdateService extends AbstractUpdateService{

    protected static $instance;
    protected $composer;
    protected $em;

    public function __construct(ContainerInterface $container) {
        parent::__construct($container);
        $this->em = $this->container->get('doctrine')->getEntityManager();
        $rootDir = realpath($this->container->getParameter('kernel.root_dir').DIRECTORY_SEPARATOR."..");
        chdir($rootDir);
    }
    public function update($remote, $username) {
        $this->setTarget($remote);
        if ($this->em->getRepository("MopaRemoteUpdateBundle:UpdateJob")->hasRunningJob()
                || ($this->config['updater'] == "cron" && $this->em->getRepository("MopaRemoteUpdateBundle:UpdateJob")->hasPendingJob())) {
            throw new Exceptions\HavingJobPendingException();
        }
        $job = $this->createNewJob($remote, $username);
        if ($this->config['updater'] == "cron") {
            $job->addMessage("UpdateJob scheduled!");
        }
        if ($this->config['updater'] == "live") {
            $this->doUpdate($job);
        }
        return $job;
    }
    protected function createNewJob($remote, $username) {
        $job = new UpdateJob();
        $job->setUsername($username);
        $job->setCreatedAt(new \DateTime());
        $job->setRemote($remote);
        $this->em->persist($job);
        $this->em->flush();
        return $job;
    }
    public function doUpdate(UpdateJob $job) {
        $this->setTarget($job->getRemote());
        $composerPath = ComposerAdapter::whichComposer($this->container->getParameter('mopa_remote_update.composer'));
        $job->setStartAt(new \DateTime());
        $this->em->persist($job);
        $this->em->flush();
        if ($this->config['preUpdate']) {
            if (!$this->runCommand($this->config['preUpdate'], $job)) {
                $job->setFinishedAt(new \DateTime());
                $this->em->persist($job);
                $this->em->flush();
                return ;
            }
        }
        try{
            if (!$this->runCommand(array($composerPath, 'update'), $job)) {
                $job->addMessage("Most probably did not find composer.phar in path of WebServer, add composer config option!\n");
            }

        }
        catch(\Exception $e) {
            $job->setStatus(UpdateJob::STATUS_FAILED);
            $job->addMessage($e->getMessage());
        }
        if ($this->config['postUpdate']) {
            $this->runCommand($this->config['postUpdate'], $job);
        }
        $job->setFinishedAt(new \DateTime());
        $this->em->persist($job);
        $this->em->flush();
    }
    protected function runCommand($command, UpdateJob $job) {
        if (is_array($command)) {
            $builder = new ProcessBuilder($command);
            $process = $builder->getProcess();
        }
        else {
            $process = new Process($command);
        }
        $process->setTimeout($this->config['timeout']);
        $process->run();
        $job->setStatus($process->isSuccessful() ? UpdateJob::STATUS_SUCCESS : UpdateJob::STATUS_FAILED);
        $job->addMessage($process->getOutput());
        $job->addMessage($process->getErrorOutput());
        $this->em->persist($job);
        $this->em->flush();
        return $process->isSuccessful();
    }
}