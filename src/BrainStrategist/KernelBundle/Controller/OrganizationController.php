<?php

namespace BrainStrategist\KernelBundle\Controller;

use BrainStrategist\KernelBundle\Entity\Organization;
use BrainStrategist\KernelBundle\Form\OrganizationForm;
use BrainStrategist\KernelBundle\Entity\User;
use FOS\UserBundle\Model\User as BaseUser;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;



class OrganizationController extends Controller
{
    /**
     * @Route("/{_locale}/organization/create",name="organize_create")
     * @Route("/{_locale}/organization/edit/{id}",name="organize_edit")
     */
    public function manageAction(Request $request,$id=null){

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem( $this->get('translator')->trans("Home"), $this->get("router")->generate("kernel"));
        $breadcrumbs->addItem( $this->get('translator')->trans("Organizations"), $this->get("router")->generate("kernel"));


        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        if($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') ){

            $params=array();
            $request = $this->container->get('request_stack')->getCurrentRequest();
            $em = $this->getDoctrine()->getEntityManager();

            if(isset($id)){
                $breadcrumbs->addItem( $this->get('translator')->trans("Edit"));
                // check if it is an edition screen to
                // retrive my shooting only if it is mine.
                $organizationEntity= $em->getRepository("BrainStrategistKernelBundle:Organization");

                if($organizationEntity->isMyOrganization($id,$currentUser->getId())){
                    $organization = $organizationEntity->find($id);
                    $form = $this->createForm(OrganizationForm::class,$organization);
                }else{
                    return $this->redirectToRoute("default");
                }

            }else{
                $organization= new Organization();
                $form = $this->createForm(OrganizationForm::class,$organization);
            }

            if ('POST' == $request->getMethod()) {

                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {

                    // $file stores the uploaded PDF file
                    /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
                    $file = $organization->getPicture();

                    // Generate a unique name for the file before saving it
                    $fileName = md5(uniqid()).'.'.$file->guessExtension();

                    // Move the file to the directory where brochures are stored
                    $file->move(
                        $this->container->getParameter('full_organization_directory'),
                        $fileName
                    );

                    // Update the 'brochure' property to store the PDF file name
                    // instead of its contents
                    $organization->setPicture($fileName);

                    $response = $form->getData();
                    $em->persist($organization);
                    $em->persist($response);
                    $em->flush();

                    if(is_null($id)) {

                        // when the user create the organization, i add himself into the organization.
                        $organization->addUserOrganization($currentUser);
                        $organization->setCreator($currentUser);
                        $currentUser->addOrganization($organization);
                        $em->persist($organization);
                        $em->persist($currentUser);
                        $em->flush();
                    }
                    return $this->redirectToRoute("default");
                }
            }
            $params = array_merge($params,
                array(
                    "form" => $form->createView(),
                ));
            return $this->render(
                'BrainStrategistKernelBundle:Organization:manage.html.twig',
                $params
            );
        }else{
            return $this->redirectToRoute("fos_user_security_login",array("type"=>"all"));
        }

    }
    /**
     * @Route("/{_locale}/organization/{slug}/projects",name="organize_access")
     */
    public function accessAction(Request $request,$slug=null){

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem( $this->get('translator')->trans("Home"), $this->get("router")->generate("kernel"));
        $breadcrumbs->addItem( $this->get('translator')->trans("Organizations"), $this->get("router")->generate("kernel"));
        $breadcrumbs->addItem( $this->get('translator')->trans("Projects"), $this->get("router")->generate("kernel"));

        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        if($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') ){

            $params=array();
            $request = $this->container->get('request_stack')->getCurrentRequest();
            $em = $this->getDoctrine()->getEntityManager();

            $params = array();

            if(isset($slug)){

                $organizationEntity= $em->getRepository("BrainStrategistKernelBundle:Organization");

                if($organizationEntity->isMyOrganization($slug,$currentUser->getId())){
                    $organization = $organizationEntity->findOneBySlug($slug);

                   if($organization->getIsActive()>0){

                       $projectsEntity= $em->getRepository("BrainStrategistProjectBundle:Project");

                       $params = array(
                           "organizationID" => $organization->getId(),
                           "userID" => $currentUser->getId(),
                           "limit"=>100,
                           "offset"=>0 );

                       $params['projects'] = $projectsEntity->getProjectsByOrganization($params);
                       $params['organization'] = $organization;
                   }

                }else{
                    return $this->redirectToRoute("default");
                }
            }
        }

        return $this->render(
            'BrainStrategistKernelBundle:Organization:access.html.twig',
            $params
        );

    }
}
