<?php
namespace Mopa\Bundle\RemoteUpdateBundle\Model;


class AbstractUpdateService{

	protected $container;
	protected $target;
	protected $config;

	public function __construct($container){
		$this->container = $container;
	}

	protected function setTarget($target){
		$this->target = $target;
		$this->config = $this->container->getParameter('mopa_remote_update.remotes.' . $target);
	}
}