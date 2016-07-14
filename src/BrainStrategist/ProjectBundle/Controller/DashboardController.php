<?php

namespace BrainStrategist\ProjectBundle\Controller;

use BrainStrategist\KernelBundle\Entity\Organization;
use BrainStrategist\ProjectBundle\Entity\Project;
use BrainStrategist\ProjectBundle\Entity\Ticket;
use BrainStrategist\KernelBundle\Entity\User;
use BrainStrategist\KernelBundle\Entity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\File;


class DashboardController extends Controller
{
    private $currentUser;

    /**
     *
     * Pre dispatcher event to check the security access of the current user
     *
     */
    public function preExecute(){

        if($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') ) {
            $this->currentUser = $this->get('security.token_storage')->getToken()->getUser();
        }else{
            throw new HttpException(400, "You are not allowed to access the Dashboard. Please register or login first");
        }
    }

    /**
     * @Route("/{_locale}/user/dashboard",name="dashboard_access")
     * @Route("/{_locale}/user/dashboard/{viewtype}",name="dashboard_kanban")
     */
    public function accessAction(Request $request,$viewtype=null){

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem( $this->get('translator')->trans("Home"), $this->get("router")->generate("kernel"));
        $breadcrumbs->addItem( $this->get('translator')->trans("Organizations"), $this->get("router")->generate("kernel"));
        $breadcrumbs->addItem( $this->get('translator')->trans("Dashboard"), $this->get("router")->generate("dashboard_access"));

        $params=array();
        $em = $this->getDoctrine()->getEntityManager();

        $params = array(
            "userID" => $this->currentUser->getId(),
            "limit"=>100,
            "offset"=>0 );

        $ticketsEntity = $em->getRepository("BrainStrategistProjectBundle:Ticket");


        if(!is_null($viewtype)){

            $params['viewtype'] = $viewtype;

            /**
             * Kanban Mode
             */
            $ticket_query = $ticketsEntity->findAllTicketByUserQuery($params);
            $ticket_query->setHydrationMode(\Doctrine\ORM\Query::HYDRATE_ARRAY);
            $ticket_results = $ticket_query->getArrayResult();

            $tickets=array();
            foreach($ticket_results as $ticket){
                $tickets[$ticket['projet']['name']][$ticket["status"]["name"]][] = $ticket;
            }
            $params['total_tickets']=sizeof($ticket_results);


        }else{
            /**
             * Classical listing view
             */
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
        }
        $params['tickets'] = $tickets;

        return $this->render(
            'BrainStrategistProjectBundle:Dashboard:overview.html.twig',
            $params
        );
    }
}
