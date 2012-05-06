<?php
namespace Mopa\Bundle\RemoteUpdateBundle\Controller;

use Mopa\Bundle\RemoteUpdateBundle\Model\LocalUpdateService;

use Mopa\Bundle\RemoteUpdateBundle\Entity\UpdateJob;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\View\View as FOSView;
use JMS\SecurityExtraBundle\Annotation\Secure;

class RestController extends Controller
{
    /**
     * @Secure(roles="ROLE_REMOTE_UPDATER")
     * @View
     */
    public function postUpdateAction(Request $request, $remote)
    {
    	$response = $this->container->get('mopa_local_update_service')->update($remote, $this->container->get('security.context')->getToken()->getUser()->getUserName());
    	if($response['status'] == "error"){
    		return FOSView::create($response, 500);
    	}
    	if($response['status'] == "pending"){
    		return FOSView::create($response, 503);
    	}
    	return $response;
    }
    /**
     * @Secure(roles="ROLE_REMOTE_UPDATER")
     * @View
     */
    public function getUpdateAction(Request $request, $remote, $count = 1)
    {
    	$response = $this->container->get('mopa_local_update_service')->check($remote, $count);
    	if(isset($response['status']) && $response['status'] == "error"){
    		return FOSView::create($response, 500);
    	}
    	return $response;
    }
}
