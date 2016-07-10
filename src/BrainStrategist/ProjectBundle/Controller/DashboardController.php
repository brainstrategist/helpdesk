<?php

namespace BrainStrategist\ProjectBundle\Controller;

use BrainStrategist\KernelBundle\Entity\Organization;
use BrainStrategist\ProjectBundle\Entity\Project;
use BrainStrategist\ProjectBundle\Entity\Ticket;
use BrainStrategist\KernelBundle\Entity\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\File;
use BrainStrategist\KernelBundle\Entity;

class DashboardController extends Controller
{
 

    /**
     * @Route("/{_locale}/user/dashboard",name="dashboard_access")
     */
    public function accessAction(Request $request){

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem( $this->get('translator')->trans("Home"), $this->get("router")->generate("kernel"));
        $breadcrumbs->addItem( $this->get('translator')->trans("Organizations"), $this->get("router")->generate("kernel"));
        $breadcrumbs->addItem( $this->get('translator')->trans("Dashboard"), $this->get("router")->generate("dashboard_access"));

        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        if($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') ) {

            $params=array();
            $em = $this->getDoctrine()->getEntityManager();

            $params = array(
                "userID" => $currentUser->getId(),
                "limit"=>100,
                "offset"=>0 );

            $ticketsEntity = $em->getRepository("BrainStrategistProjectBundle:Ticket");

            if(null !== $request->query->getInt('page') && !isset($page)){
                $page = 1;
            }
            if(null !== $request->query->getInt('limit') && !isset($limit)){
                $limit = 10;
            }

            $ticket_query = $ticketsEntity->findAllTicketByUserQuery($params);

            $paginator  = $this->get('knp_paginator');
            $tickets = $paginator->paginate(
                $ticket_query,
                $page,
                $limit
            );
            $params['tickets'] = $tickets;
           
        }else{
            return $this->redirectToRoute("fos_user_security_login",array("type"=>"all"));
        }

        return $this->render(
            'BrainStrategistProjectBundle:Dashboard:overview.html.twig',
            $params
        );
    }
}
