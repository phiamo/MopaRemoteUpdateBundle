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
    	return $this->container->get('mopa_local_update_service')->update($remote, $this->container->get('security.context')->getToken()->getUser()->getUserName());
    }
    /**
     * @Secure(roles="ROLE_REMOTE_UPDATER")
     * @View
     */
    public function getUpdatesAction(Request $request, $remote)
    {
		return $this->getDoctrine()->getEntityManager()
			->getRepository("MopaRemoteUpdateBundle:UpdateJob")
			->getLastJobs($request->get('count', 1));
    }
}
