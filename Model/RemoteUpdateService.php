<?php
namespace Mopa\Bundle\RemoteUpdateBundle\Model;


class RemoteUpdateService{

	protected $container;

	protected $target;
	protected $config;

    public function __construct($container, $buzz){
        $this->container = $container;
        $this->buzz = $buzz;
    }

    public function setTarget($target){
    	$this->target = $target;
        $this->config = $this->container->getParameter('mopa_remote_update.remotes.' . $target);
    	return $this;
    }
    protected function getTargetApiEntryPoint(){
		return $this->config['url'];
    }
    public function update(){
    	$this->container->get('mopa_wsse_auth_listener')
    		->setCredentials($this->config['username'], $this->config['password']);
    	$response = $this->buzz->get($this->getTargetApiEntryPoint());
    	var_dump($response);
    }
}