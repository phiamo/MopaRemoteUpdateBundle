<?php
namespace Mopa\Bundle\RemoteUpdateBundle\Model;


class RemoteUpdateService{

    protected $container;

    public function __construct($container){
        $this->container = $container;
        $buzz = $this->container->get('buzz');
    }
}