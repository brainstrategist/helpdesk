<?php

namespace BrainStrategist\KernelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class DefaultController extends Controller
{
    /**
     * @Route("/{_locale}/", name="kernel")
     * @Route("/", name="default")
     */
    public function indexAction(Request $request)
    {
	    $breadcrumbs = $this->get("white_october_breadcrumbs");
	    $breadcrumbs->addItem( $this->get('translator')->trans("Home"), $this->get("router")->generate("kernel"));
        $breadcrumbs->addItem( $this->get('translator')->trans("Organizations"), $this->get("router")->generate("kernel"));

        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        if($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') ) {

            $em = $this->getDoctrine()->getEntityManager();
            $params = array("userID" => $currentUser->getId(),
                "limit"=>100,
                "offset"=>0 );

            $listOrganization = $em->getRepository("BrainStrategistKernelBundle:Organization")->findMyOrganizations($params);

            return $this->render('BrainStrategistKernelBundle:Default:index.html.twig',
                array(
                    "listOrganization" => $listOrganization
                ));

        }


        return $this->render('BrainStrategistKernelBundle:Default:index.html.twig');
    }
}
