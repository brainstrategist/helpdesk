<?php

namespace BrainStrategist\ProjectBundle\Controller;

use BrainStrategist\KernelBundle\Entity\Organization;
use BrainStrategist\ProjectBundle\Entity\Project;
use BrainStrategist\ProjectBundle\Entity\Ticket;
use BrainStrategist\ProjectBundle\Entity\Ticket_Comment;
use BrainStrategist\ProjectBundle\Entity\Ticket_Log;
use BrainStrategist\ProjectBundle\Form\ProjectForm;
use BrainStrategist\KernelBundle\Entity\User;

use BrainStrategist\ProjectBundle\Form\TicketForm;
use BrainStrategist\ProjectBundle\Form\CommentForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\File;
use BrainStrategist\KernelBundle\Entity;

class TicketController extends Controller
{
    /**
     * @Route("/{_locale}/project/{slug}/ticket/list",name="ticket_list")
     */
    public function listAction(Request $request,$slug=null){
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        if($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') ) {
            $params=array();
            $request = $this->container->get('request_stack')->getCurrentRequest();
            $em = $this->getDoctrine()->getEntityManager();

            $projectEntity = $em->getRepository("BrainStrategistProjectBundle:Project");

            if(isset($slug)) {

                if ($projectEntity->isMyProject($slug, $currentUser->getId())) {
                    $project = $projectEntity->findOneBySlug($slug);

                    $ticketsEntity = $em->getRepository("BrainStrategistProjectBundle:Ticket");

                    $params = array(
                        "projectID" => $project->getId(),
                        "limit"=>100,
                        "offset"=>0 );

                    $tickets = $ticketsEntity->findAllTicketByProjectId($params);
                    $params['tickets'] = $tickets;

                }

            }
            return $this->render(
                'BrainStrategistProjectBundle:Ticket:list.html.twig',
                $params
            );
        }

    }


    /**
 * @Route("/{_locale}/project/{slug}/ticket/create",name="ticket_create")
 */
    public function createAction(Request $request,$slug=null){


        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        if($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') ) {

            $params=array();
            $request = $this->container->get('request_stack')->getCurrentRequest();
            $em = $this->getDoctrine()->getEntityManager();

            $projectEntity = $em->getRepository("BrainStrategistProjectBundle:Project");

            if(isset($slug)) {

                if ($projectEntity->isMyProject($slug, $currentUser->getId())) {
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
                        $ticket->setCreator($currentUser);
                        $ticket->setSeverity($response->getSeverity());

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
        }else{
            return $this->redirectToRoute("fos_user_security_login",array("type"=>"all"));
        }

        return $this->render(
            'BrainStrategistProjectBundle:Ticket:create.html.twig',
            $params
        );
    }
    /**
     * @Route("/{_locale}/project/{slug}/ticket/view/{id}",name="ticket_view")
     */
    public function viewAction(Request $request,$slug=null,$id=null){


        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        if($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') ) {

            $params=array();
            $request = $this->container->get('request_stack')->getCurrentRequest();
            $em = $this->getDoctrine()->getEntityManager();

            $projectEntity = $em->getRepository("BrainStrategistProjectBundle:Project");
            $ticketEntity = $em->getRepository("BrainStrategistProjectBundle:Ticket");
            $statusEntity = $em->getRepository("BrainStrategistProjectBundle:Ticket_Status");

            if(isset($slug) && isset($id)) {

                if ($projectEntity->isMyProject($slug, $currentUser->getId())) {

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
                        $ticket_comment->setUserComment($currentUser);
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

        }else{
            return $this->redirectToRoute("fos_user_security_login",array("type"=>"all"));
        }

        return $this->render(
            'BrainStrategistProjectBundle:Ticket:view.html.twig',
            $params
        );
    }
}
