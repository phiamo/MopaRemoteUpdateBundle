<?php
namespace Mopa\Bundle\RemoteUpdateBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/mopa/remote/update/api/", name="mopa_remote_update_api")
     */
    public function updateAction(Request $request)
    {
		$this->getDoctrine()
			 ->getEntityManager("mopa_remote_update")
			 ->getRepository("MopaRemoteUpdateBundle:UpdateJob")
			 ->findAll();
        return array();
    }
}
