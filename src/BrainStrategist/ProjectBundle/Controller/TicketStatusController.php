<?php

namespace BrainStrategist\ProjectBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;

use BrainStrategist\KernelBundle\Entity\Organization;
use BrainStrategist\ProjectBundle\Entity\Project;
use BrainStrategist\ProjectBundle\Entity\Ticket_Status;
use BrainStrategist\ProjectBundle\Form\StatusForm;
use BrainStrategist\KernelBundle\Entity\User;

use BrainStrategist\ProjectBundle\Form\TicketForm;
use Symfony\Component\HttpFoundation\File\File;
use BrainStrategist\KernelBundle\Entity;

class TicketStatusController extends Controller
{

    private $currentUser;
    private $breadcrumbs;
    /**
     *
     * Pre dispatcher event to check the security access of the current user
     *
     */
    public function preExecute(){

        if($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') ) {
            $this->currentUser = $this->get('security.token_storage')->getToken()->getUser();

            $this->breadcrumbs = $this->get("white_october_breadcrumbs");
            $this->breadcrumbs->addItem( $this->get('translator')->trans("Home"), $this->get("router")->generate("kernel"));
            $this->breadcrumbs->addItem( $this->get('translator')->trans("Organizations"), $this->get("router")->generate("kernel"));
        }else{
            throw new HttpException(400, "You are not allowed to access Project. Please register or login first");
        }
    }


    /**
     * @Route("/{_locale}/project/{slug}/status/create",name="status_create")
     * @Route("/{_locale}/project/{slug}/status/edit/{id}",name="status_edit")
     */
    public function manageAction(Request $request,$id=null,$slug=null){

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem( $this->get('translator')->trans("Home"), $this->get("router")->generate("kernel"));
        $breadcrumbs->addItem( $this->get('translator')->trans("Organizations"), $this->get("router")->generate("kernel"));
        $breadcrumbs->addItem( $this->get('translator')->trans("Projects"), $this->get("router")->generate("organize_access",array("slug"=>$slug)));

        $params=array();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $em = $this->getDoctrine()->getManager();
        $organizationEntity= $em->getRepository("BrainStrategistKernelBundle:Organization");
        $projectEntity= $em->getRepository("BrainStrategistProjectBundle:Project");
        $statusEntity= $em->getRepository("BrainStrategistProjectBundle:Ticket_Status");

        if(isset($slug)){
            $project = $projectEntity->findBySlug($slug);
            $ticket_status= new Ticket_Status();
        }else{
            return $this->redirectToRoute("default");
        }
        if(isset($id)){
            $breadcrumbs->addItem( $this->get('translator')->trans("Edit"));
            // check if it is an edition screen to
            // retrive my shooting only if it is mine.
            $status = $statusEntity->find($id);
            if($projectEntity->isMyProject($project[0]->getId(),$this->currentUser->getId()) && $project[0]->getCreator()->getId()==$this->currentUser->getId()){
                $form = $this->createForm(StatusForm::class,$status);
            }else{
                return $this->redirectToRoute("default");
            }

        }else{
            $form = $this->createForm(StatusForm::class,$ticket_status);
        }

        if ('POST' == $request->getMethod()) {

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $ticket_status->setProject($project[0]);
                $response = $form->getData();
                if(is_null($id)) {
                    $em->persist($ticket_status);
                }
                $em->persist($response);
                $em->flush();

                return $this->redirectToRoute("status_list", array('slug'=>$slug));
            }
        }
        $params = array_merge($params,
            array(
                "form" => $form->createView(),
            ));
        return $this->render(
            'BrainStrategistProjectBundle:Status:manage.html.twig',
            $params
        );


    }

    /**
     * @Route("/{_locale}/project/{slug}/status/list",name="status_list")
     */
    public function listAction(Request $request,$slug=null){


        $this->preExecute();

        $params=array();
        $em = $this->getDoctrine()->getManager();

        $projectEntity = $em->getRepository("BrainStrategistProjectBundle:Project");

        if(isset($slug)) {

            if ($projectEntity->isMyProject($slug, $this->currentUser->getId())) {

                $project = $projectEntity->findOneBySlug($slug);
                $params = array("projectID" => $project->getId());

                // Get all severity by project
                $statusEntity = $em->getRepository("BrainStrategistProjectBundle:Ticket_Status");
                $status = $statusEntity->findAllByProjectId($project->getId());
                $statusQuery = $status->getQuery();
                $statusQuery->setHydrationMode(\Doctrine\ORM\Query::HYDRATE_ARRAY);

                $params['status_list'] = $statusQuery->getArrayResult();
                $params['slug'] = $slug;
                $params['projet'] = $project;
            }

            $this->breadcrumbs->addItem( $this->get('translator')->trans("Projects"), $this->get("router")->generate("organize_access",array("slug"=>$slug)));
            $this->breadcrumbs->addItem( $project->getName(), $this->get("router")->generate("project_access",array("slug"=>$slug)));
            $this->breadcrumbs->addItem( $this->get('translator')->trans("Severities"), $this->get("router")->generate("severity_list",array("slug"=>$slug)));

        }

        return $this->render(
            'BrainStrategistProjectBundle:Status:list.html.twig',
            $params
        );
    }
}