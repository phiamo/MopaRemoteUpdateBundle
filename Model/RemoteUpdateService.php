<?php
namespace Mopa\Bundle\RemoteUpdateBundle\Model;


use Buzz\Browser;

use Symfony\Component\Routing\RouterInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;

class RemoteUpdateService extends AbstractUpdateService{

	protected $buzz;
	protected $router;

    public function __construct(ContainerInterface $container, Browser $buzz, RouterInterface $router){
    	parent::__construct($container);
        $this->buzz = $buzz;
        $this->router = $router;
    }
	protected function getTargetApiEntryPoint($route, array $parameters){
		return $this->config['url'] . $this->router->generate($route, $parameters);
	}
    public function update($remote){
    	$this->setTarget($remote);
    	$this->container->get('mopa_wsse_auth_listener')
    		->setCredentials($this->config['username'], $this->config['password']);
    	return $this->doUpdate();
    }
	protected function doUpdate(){
		$path = $this->getTargetApiEntryPoint("mopa_update_api_post_update", array("remote" => $this->target));
    	$response = $this->buzz->post($path);
		$json = json_decode($response->getContent());
		if($code = json_last_error()){
			throw new \RuntimeException("Couldnt decode Json: Code $code".$response->getContent());
		}
    	return $json;
	}
}