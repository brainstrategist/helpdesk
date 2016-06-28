<?php

namespace BrainStrategist\ProjectBundle\Controller;

use BrainStrategist\ProjectBundle\Entity\Project;
use BrainStrategist\ProjectBundle\Form\ProjectForm;
use BrainStrategist\KernelBundle\Entity\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BrainStrategist\KernelBundle\Entity;

class DefaultController extends Controller
{
    /**
     * @Route("/{_locale}/organization/{slug}/project/create",name="project_create")
     * @Route("/{_locale}/organization/{slug}/project/edit/{id}",name="project_edit")
     */
    public function manageAction(Request $request,$id=null,$slug=null){

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem( $this->get('translator')->trans("Home"), $this->get("router")->generate("kernel"));
        $breadcrumbs->addItem( $this->get('translator')->trans("Organizations"), $this->get("router")->generate("kernel"));
        $breadcrumbs->addItem( $this->get('translator')->trans("Projects"), $this->get("router")->generate("organize_access",array("slug"=>$slug)));


        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        if($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') ){

            $params=array();
            $request = $this->container->get('request_stack')->getCurrentRequest();
            $em = $this->getDoctrine()->getEntityManager();
            $organizationEntity= $em->getRepository("BrainStrategistKernelBundle:Organization");
            $projectEntity= $em->getRepository("BrainStrategistProjectBundle:Project");

            if(isset($id) && isset($slug)){
                $breadcrumbs->addItem( $this->get('translator')->trans("Edit"));
                // check if it is an edition screen to
                // retrive my shooting only if it is mine.

                if($organizationEntity->isMyOrganization($id,$currentUser->getId())){
                    $project = $projectEntity->find($id);
                    $form = $this->createForm(ProjectForm::class,$project);
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

                    $response = $form->getData();
                    $em->persist($response);
                    $em->flush();

                    if(is_null($id)) {
                        $organization = $organizationEntity->findOneBySlug($slug);
                        // when the user create the organization, i add himself into the organization.
                        $project->addUsersProject($currentUser);
                        $project->setCreator($currentUser);
                        $project->setOrganization($organization);
                        $currentUser->addProject($project);
                        $em->persist($project);
                        $em->persist($currentUser);
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
        }else{
            return $this->redirectToRoute("fos_user_security_login",array("type"=>"all"));
        }

    }
}
