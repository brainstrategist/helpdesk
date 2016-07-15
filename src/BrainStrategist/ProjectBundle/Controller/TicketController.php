<?php

namespace BrainStrategist\ProjectBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

use BrainStrategist\KernelBundle\Entity\Organization;
use BrainStrategist\ProjectBundle\Entity\Project;
use BrainStrategist\ProjectBundle\Entity\Ticket;
use BrainStrategist\ProjectBundle\Entity\Ticket_Comment;
use BrainStrategist\ProjectBundle\Entity\Ticket_Log;
use BrainStrategist\ProjectBundle\Form\ProjectForm;
use BrainStrategist\KernelBundle\Entity\User;

use BrainStrategist\ProjectBundle\Form\TicketForm;
use BrainStrategist\ProjectBundle\Form\CommentForm;
use Symfony\Component\HttpFoundation\File\File;
use BrainStrategist\KernelBundle\Entity;


class TicketController extends Controller
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
            throw new HttpException(400, "You are not allowed to access ticket. Please register or login first");
        }
    }


    /**
     * @Route("/{_locale}/project/{slug}/ticket/list",name="ticket_list")
     */
    public function listAction(Request $request,$slug=null,$page=null,$filters=null,$limit=null){

        $this->preExecute();

        $params=array();
        $em = $this->getDoctrine()->getEntityManager();

        $projectEntity = $em->getRepository("BrainStrategistProjectBundle:Project");

        if(isset($slug)) {

            if ($projectEntity->isMyProject($slug, $this->currentUser->getId())) {
                $project = $projectEntity->findOneBySlug($slug);

                $ticketsEntity = $em->getRepository("BrainStrategistProjectBundle:Ticket");

                $params = array("projectID" => $project->getId());

                // Get all severity by project
                $severityEntity = $em->getRepository("BrainStrategistProjectBundle:Severity");
                $severity = $severityEntity->findAllByProjectId($project->getId());
                $severityQuery = $severity->getQuery();
                $severityQuery->setHydrationMode(\Doctrine\ORM\Query::HYDRATE_ARRAY);

                $params['severities'] = $severityQuery->getArrayResult();

                // Get all category by project
                $categoryEntity = $em->getRepository("BrainStrategistProjectBundle:Ticket_Category");
                $categories = $categoryEntity->findAll();
                $params['categories'] = $categories;

                // Get all status by project
                $statusEntity = $em->getRepository("BrainStrategistProjectBundle:Ticket_status");
                $status = $statusEntity->findAllByProjectId($project->getId());
                $statusQuery = $status->getQuery();
                $statusQuery->setHydrationMode(\Doctrine\ORM\Query::HYDRATE_ARRAY);

                $params['status_list'] = $statusQuery->getArrayResult();

                if(isset($filters))
                    $params['filters'] = $filters;

                // pagination
                if(null !== $request->query->getInt('page') && !isset($page)){
                    $page = 1;
                }
                // nb results per page
                if(null !== $request->query->getInt('limit') && !isset($limit)){
                    $limit = 10;
                }

                $ticket_query = $ticketsEntity->findAllTicketByProjectIdQuery($params);

                $paginator  = $this->get('knp_paginator');
                $tickets = $paginator->paginate(
                    $ticket_query,
                    $page,
                    $limit
                );
                $params['tickets'] = $tickets;

            }

        }
        return $this->render(
            'BrainStrategistProjectBundle:Ticket:list.html.twig',
            $params
        );


    }


    /**
     * @Route("/{_locale}/project/{slug}/ticket/create",name="ticket_create")
     */
    public function createAction(Request $request,$slug=null){

        $this->preExecute();

        $params=array();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $em = $this->getDoctrine()->getEntityManager();

        $projectEntity = $em->getRepository("BrainStrategistProjectBundle:Project");

        if(isset($slug)) {

            if ($projectEntity->isMyProject($slug, $this->currentUser->getId())) {
                $project = $projectEntity->findOneBySlug($slug);
                $params['project'] = $project;
                $organizationEntity = $em->getRepository("BrainStrategistKernelBundle:Organization");
                $organization = $organizationEntity->find($project->getOrganization()->getId());
                $params['organization'] = $organization;

                $ticket= new Ticket();
                $form = $this->createForm(TicketForm::class,$ticket,  array('attr'=> array('project_id' => $project->getId())));
                $params = array_merge($params,
                    array(
                        "form" => $form->createView(),
                    ));
            }
        }

        if ('POST' == $request->getMethod()) {

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $response = $form->getData();

                /**
                 * Check ticket before sending
                 */
                $valid = true;
                $notice = null;
                $type_notice = null;

                if($response->getDescription() == null){
                    $valid = false;
                    $notice =  'The description is empty';
                    $type_notice =  'alert';
                }

                if($valid){

                    $project->addProjectTicket($ticket);
                    $ticket->setProjet($project);
                    $ticket->setCreator($this->currentUser);
                    $ticket->setSeverity($response->getSeverity());
                    $ticket->setPriority($response->getPriority());

                    $ticket_log = new Ticket_Log();
                    $ticket_log->setContentLog('Creation of the ticket');
                    $ticket_log->setTicket($ticket);
                    $ticket->addLog($ticket_log);

                    $em->persist($ticket);
                    $em->persist($ticket_log);
                    $em->persist($project);
                    $em->persist($response);
                    $em->flush();

                    foreach($response->getAssignedUsers() as $user){

                        $user->addUserTicket($ticket);
                        $em->persist($user);
                        $em->flush();
                    }

                }
            }
            return $this->forward("BrainStrategistProjectBundle:Project:access",array('slug'=>$slug,'view' => 'ticket-create','notice'=>$notice,'type_notice'=>$type_notice));
        }

        return $this->render(
            'BrainStrategistProjectBundle:Ticket:create.html.twig',
            $params
        );
    }

    /**
     * @Route("/{_locale}/ajax/ticket/status",name="ticket_status_ajax")
     */
    public function ajaxStatusAction(Request $request){

        $this->preExecute();

        $params=array();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $em = $this->getDoctrine()->getEntityManager();

        $ticketEntity = $em->getRepository("BrainStrategistProjectBundle:Ticket");
        $statusEntity = $em->getRepository("BrainStrategistProjectBundle:Ticket_Status");

        if ('POST' == $request->getMethod()) {

            $ticket_id = $request->request->get('ticket');
            $status_id =  $request->request->get('status');

            if($ticketEntity->isMyTicket($ticket_id,$this->currentUser->getId())){

                // retreive the new order from JS and build a more friendly array for
                // an easy processing by doctrine
                $orders =  $request->request->get('order');
                $ids =array();
                foreach($orders as  $order){
                    $ids[$order["new_order"]]=$order["ticket_id"];
                }

                foreach ($ticketEntity->findById($ids) as $obj) {
                    $obj->setOrder(array_search($obj->getId(), $ids));
                }

                $status = $statusEntity->find($status_id);
                $ticket = $ticketEntity->find($ticket_id);
                $ticket->setStatus($status);
                $em->persist($ticket);
                $em->flush();
                return new JsonResponse(array('message'=>"Done"));

            }else{
                return new JsonResponse(array('message'=>"Not my ticket"));
            }

        }

        return new JsonResponse(array('message'=>"Not a POST request"));
    }
    /**
     * @Route("/{_locale}/project/{slug}/ticket/view/{id}",name="ticket_view")
     * @Route("/{_locale}/project/{slug}/ticket/view/{id}/mode/{mode}",name="ticket_view_popup")
     */
    public function viewAction(Request $request,$slug=null,$id=null,$mode=null){

            $params=array();
            $request = $this->container->get('request_stack')->getCurrentRequest();
            $em = $this->getDoctrine()->getEntityManager();

            $projectEntity = $em->getRepository("BrainStrategistProjectBundle:Project");
            $ticketEntity = $em->getRepository("BrainStrategistProjectBundle:Ticket");
            $statusEntity = $em->getRepository("BrainStrategistProjectBundle:Ticket_Status");

            if(isset($mode)){
                $params['mode']=$mode;
            }
            if(isset($slug) && isset($id)) {

                if ($projectEntity->isMyProject($slug, $this->currentUser->getId())) {

                    $project = $projectEntity->findOneBySlug($slug);
                    $ticket = $ticketEntity->findOneById($id);
                    $params['project'] = $project;
                    $params['ticket'] = $ticket;
                    $params['status_list'] = $statusEntity->findAllByProjectId($params['project']->getId());

                    $ticket_comment = new Ticket_Comment();
                    $form = $this->createForm(CommentForm::class,$ticket_comment,  array('attr'=> array('project_id' => $project->getId())));

                    $params = array_merge($params,
                        array(
                            "form" => $form->createView(),
                        ));

                }
            }
            if ('POST' == $request->getMethod()) {

                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {

                    $response = $form->getData();
                    $ticket = $ticketEntity->find($id);

                    if(!is_null($response->getContentComment())){
                        $ticket_comment->setUserComment($this->currentUser);
                        $ticket_comment->setTicket($ticket);
                        $em->persist($ticket_comment);
                        $em->persist($response);
                    }


                    $ticket->setStatus($response->getTicketStatus());
                    $em->persist($ticket);
                    $em->flush();

                    return $this->redirectToRoute("ticket_view",array("id"=>$id,"slug"=>$slug));
                }
            }

        return $this->render(
            'BrainStrategistProjectBundle:Ticket:view.html.twig',
            $params
        );
    }
}
