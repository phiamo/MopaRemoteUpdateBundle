<?php
namespace Mopa\Bundle\RemoteUpdateBundle\Model;


use Buzz\Browser;

use Symfony\Component\Routing\RouterInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;

class RemoteUpdateService extends AbstractUpdateService{

    protected $buzz;
    protected $router;
    protected $serializer;

    public function __construct(ContainerInterface $container, Browser $buzz, RouterInterface $router) {
        parent::__construct($container);
        $this->buzz = $buzz;
        $this->router = $router;
        $this->serializer = $this->container->get('serializer');
    }
    protected function getTargetApiEntryPoint($route, array $parameters) {
        return $this->config['url'] . $this->router->generate($route, $parameters);
    }
    protected function communicate($method, $route, $parameters) {
        $path = $this->getTargetApiEntryPoint($route, $parameters);
        $response = $this->buzz->$method($path);
        if (200 != $response->getStatusCode()) {
            if (503 == $response->getStatusCode()) {
                throw $this->serializer->deserialize($response->getContent(), 'Mopa\Bundle\RemoteUpdateBundle\Model\Exceptions\HavingJobPendingException', 'json');
            }
            $error =  json_decode($response->getContent());
            if ($code = json_last_error()) {
                throw new \RuntimeException("Couldnt decode Json for $path: Code $code\n Response:".$response->getContent());
            }
            throw new Exceptions\RemoteException($error->message, $error->status_code);
        }
        return $response;
    }
    public function check($remote, $count) {
        $this->setTarget($remote);
        $response = $this->communicate('get', "mopa_update_api_get_updates", array("remote" => $this->target, "count" => $count));
        return $this->serializer->deserialize($response->getContent(), 'array<Mopa\Bundle\RemoteUpdateBundle\Entity\UpdateJob>', 'json');
    }
    public function update($remote) {
        $this->setTarget($remote);
        $response = $this->communicate('post', "mopa_update_api_post_update", array("remote" => $this->target));
        return $this->serializer->deserialize($response->getContent(), 'Mopa\Bundle\RemoteUpdateBundle\Entity\UpdateJob', 'json');
    }
}