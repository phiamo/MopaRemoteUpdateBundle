<?php
namespace Mopa\Bundle\RemoteUpdateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/mopa/remote/update/{token}", name="mopa_remote_update_action")
     * @Template()
     */
    public function updateAction($token)
    {
        return array();
    }
}
