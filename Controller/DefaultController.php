<?php
namespace Mopa\Bundle\RemoteUpdateBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/mopa/remote/updateapi/", name="mopa_remote_update_api")
     * @Template()
     */
    public function updateAction(Request $request)
    {
    	echo "GOT INSIDE";
    	//var_dump($request);
    	//var_dump($this->get('security.context')->getToken());
    	exit;
        return array();
    }
}
