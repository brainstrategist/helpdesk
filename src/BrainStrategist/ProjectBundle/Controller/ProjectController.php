<?php

namespace BrainStrategist\ProjectBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;

use BrainStrategist\KernelBundle\Entity\Organization;
use BrainStrategist\ProjectBundle\Entity\Project;
use BrainStrategist\ProjectBundle\Entity\Ticket;
use BrainStrategist\ProjectBundle\Form\ProjectForm;
use BrainStrategist\KernelBundle\Entity\User;

use BrainStrategist\ProjectBundle\Form\TicketForm;
use Symfony\Component\HttpFoundation\File\File;
use BrainStrategist\KernelBundle\Entity;

class ProjectController extends Controller
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
            throw new HttpException(400, "You are not allowed to access Project. Please register or login first");
        }
    }


    /**
     * @Route("/{_locale}/organization/{slug}/project/create",name="project_create")
     * @Route("/{_locale}/organization/{slug}/project/edit/{id}",name="project_edit")
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

        if(isset($id) && isset($slug)){
            $breadcrumbs->addItem( $this->get('translator')->trans("Edit"));
            // check if it is an edition screen to
            // retrive my shooting only if it is mine.

            $project = $projectEntity->find($id);

            if($projectEntity->isMyProject($id,$this->currentUser->getId()) && $project->getCreator()->getId()==$this->currentUser->getId()){

                $form = $this->createForm(ProjectForm::class,$project);

                if(!is_null($project->getPicture()) && $project->getPicture()!="" ){
                    $params['picture'] = $project->getPicture();
                    $project->setPicture(
                        new File($this->getParameter('full_project_directory').'/'.$project->getPicture())
                    );
                }

            }else{
                return $this->redirectToRoute("default");
            }

        }else{
            $project= new Project();
            $form = $this->createForm(ProjectForm::class,$project);
        }

        if ('POST' == $request->getMethod()) {

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
                $file = $project->getPicture();

                if(!is_null($project->getPicture()) && $project->getPicture()!="" ){
                    // Generate a unique name for the file before saving it
                    $fileName = md5(uniqid()).'.'.$file->guessExtension();

                    // Move the file to the directory where brochures are stored
                    $file->move(
                        $this->container->getParameter('full_project_directory'),
                        $fileName
                    );
                    // Update the 'brochure' property to store the PDF file name
                    // instead of its contents
                    $project->setPicture($fileName);
                }
                $response = $form->getData();
                $em->persist($project);
                $em->persist($response);
                $em->flush();

                if(is_null($id)) {
                    $organization = $organizationEntity->findOneBySlug($slug);
                    // when the user create the organization, i add himself into the organization.
                    $project->addUsersProject($this->currentUser);
                    $project->setCreator($this->currentUser);
                    $project->setOrganization($organization);
                    $organization->addProjectsOrganization($project);
                    $this->currentUser->addProject($project);

                    $em->persist($project);
                    $em->persist($organization);
                    $em->persist($this->currentUser);
                    $em->flush();
                }

                return $this->redirectToRoute("organize_access", array('slug'=>$slug));
            }
        }
        $params = array_merge($params,
            array(
                "form" => $form->createView(),
            ));
        return $this->render(
            'BrainStrategistProjectBundle:Project:manage.html.twig',
            $params
        );


    }

    /**
     * @Route("/{_locale}/project/{slug}/dashboard",name="project_access")
     * @Route("/{_locale}/project/{slug}/view/{view}",name="project_view")
     */
    public function accessAction(Request $request,$slug=null,$view=null){

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem( $this->get('translator')->trans("Home"), $this->get("router")->generate("kernel"));
        $breadcrumbs->addItem( $this->get('translator')->trans("Organizations"), $this->get("router")->generate("kernel"));
        $breadcrumbs->addItem( $this->get('translator')->trans("Projects"), $this->get("router")->generate("organize_access",array("slug"=>$slug)));

            $params=array();
            $request = $this->container->get('request_stack')->getCurrentRequest();
            $em = $this->getDoctrine()->getManager();

            $projectEntity = $em->getRepository("BrainStrategistProjectBundle:Project");

            if(isset($slug)) {

                if ($projectEntity->isMyProject($slug, $this->currentUser->getId())) {
                    $project = $projectEntity->findOneBySlug($slug);
                    $params['project'] = $project;
                    $organizationEntity = $em->getRepository("BrainStrategistKernelBundle:Organization");
                    $organization = $organizationEntity->find($project->getOrganization()->getId());
                    $params['organization'] = $organization;

                    $notice = $request->attributes->get('notice');
                    $type_notice = $request->attributes->get('type_notice');
                    if(isset($notice)){
                        $params['notice']=$notice;
                        $params['type_notice']=$type_notice;
                    }

                    if(isset($view)){
                        $params['view'] =  $this->renderView(
                            'BrainStrategistProjectBundle:Project:parts/'.$view.'.html.twig',
                            $params
                        );
                    }
                }
            }


        return $this->render(
            'BrainStrategistProjectBundle:Project:overview.html.twig',
            $params
        );
    }
}