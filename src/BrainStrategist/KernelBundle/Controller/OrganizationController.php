<?php

namespace BrainStrategist\KernelBundle\Controller;

use BrainStrategist\KernelBundle\Entity\Organization;
use BrainStrategist\KernelBundle\Entity\User;
use FOS\UserBundle\Model\User as BaseUser;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use BrainStrategist\KernelBundle\Form\OrganizationForm;

class OrganizationController extends Controller
{
    /**
     * @Route("/{_locale}/organization/create",name="organize_create")
     * @Route("/{_locale}/organization/edit/{id}",name="organize_edit")
     */
    public function manageAction(Request $request,$id=null){

        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        if($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') ){

            $params=array();
            $request = $this->container->get('request_stack')->getCurrentRequest();
            $em = $this->getDoctrine()->getEntityManager();

            if(isset($id)){

                // check if it is an edition screen to
                // retrive my shooting only if it is mine.
                $organizationEntity= $em->getRepository("BrainStrategistKernelBundle:Organization");

                if($organizationEntity->isMyOrganization($id,$currentUser->getId())){
                    $organization = $organizationEntity->find($id);
                    $form = $this->createForm(OrganizationForm::class,$organization);
                    $params = array('shooting'=>$organization);
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

                    $response = $form->getData();

                    $em->persist($response);
                    $em->flush();

                    if(is_null($id)) {

                        // when the user create the organization, i add himself into the organization.
                        $organization->addUserOrganization($currentUser);
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

}
