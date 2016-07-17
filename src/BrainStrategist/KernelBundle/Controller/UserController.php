<?php

namespace BrainStrategist\KernelBundle\Controller;

use BrainStrategist\KernelBundle\Entity\Organization;
use BrainStrategist\KernelBundle\Form\OrganizationForm;
use BrainStrategist\KernelBundle\Entity\User;
use FOS\UserBundle\Model\User as BaseUser;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    private $currentUser;

    /**
     *
     * Pre dispatcher event to check the security access of the current user
     *
     */
    public function preExecute()
    {

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem($this->get('translator')->trans("Home"), $this->get("router")->generate("kernel"));
        $breadcrumbs->addItem($this->get('translator')->trans("Organizations"), $this->get("router")->generate("kernel"));
        $breadcrumbs->addItem($this->get('translator')->trans("Users"), $this->get("router")->generate("kernel"));

        if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->currentUser = $this->get('security.token_storage')->getToken()->getUser();
        } else {
            throw new HttpException(400, "You are not allowed to access Organization. Please register or login first");
        }
    }

    /**
     * @Route("/{_locale}/organization/{slug}/users",name="organize_users")
     */
    public function listAction(Request $request, $slug = null)
    {

        $params = array();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $em = $this->getDoctrine()->getEntityManager();

        $params = array();

        if (isset($slug)) {

            $organizationEntity = $em->getRepository("BrainStrategistKernelBundle:Organization");

            if ($organizationEntity->isMyOrganization($slug, $this->currentUser->getId())) {
                $organization = $organizationEntity->findOneBySlug($slug);

                if ($organization->getIsActive() > 0) {
                    $params['organization'] = $organization;
                    $paramsQuery = array("organizationID" => $organization->getId());

                    $userEntity = $em->getRepository("BrainStrategistKernelBundle:User");
                    $params['users'] = $userEntity->getUsersByOrganization($paramsQuery);

                }

            } else {
                return $this->redirectToRoute("default");
            }
        }

        return $this->render(
            'BrainStrategistKernelBundle:Users:list.html.twig',
            $params
        );

    }

    /**
     * @Route("/{_locale}/organization/{slug}/users/search/",name="search_user")
     */
    public function searchAction(Request $request, $slug = null)
    {


        $params = array();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $em = $this->getDoctrine()->getEntityManager();

        $params = array();

        if (isset($slug)) {

            $organizationEntity = $em->getRepository("BrainStrategistKernelBundle:Organization");

            if ($organizationEntity->isMyOrganization($slug, $this->currentUser->getId())) {
                $organization = $organizationEntity->findOneBySlug($slug);

                if ($organization->getIsActive() > 0) {

                    $term = $request->query->get('term');

                    $userEntity = $em->getRepository("BrainStrategistKernelBundle:User");
                    $response = $userEntity->findUsers($term);
                    return new JsonResponse($response);

                }

            } else {
                return new JsonResponse(array('message' => "false"));
            }
        }

    }

    /**
     * @Route("/{_locale}/organization/{slug}/users/add/",name="organization_user_add")
     */
    public function addOrganizationUserAction(Request $request,$slug=null){


        $params = array();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $em = $this->getDoctrine()->getEntityManager();

        if ('POST' == $request->getMethod()) {
            if (isset($slug)) {

                $organizationEntity = $em->getRepository("BrainStrategistKernelBundle:Organization");

                $user = $request->request->get('user');
                $userEntity = $em->getRepository("BrainStrategistKernelBundle:User");
                $user_to_add = $userEntity->findOneByUsername($user);

                if (!$organizationEntity->isMyOrganization($slug, $user_to_add->getId())) {
                    $organization = $organizationEntity->findOneBySlug($slug);

                    $organization->addUsersOrganization($user_to_add);
                    $user_to_add->addOrganization($organization);

                    $em->persist($organization);
                    $em->persist($user_to_add);
                    $em->flush();

                }

            }
        }
        return $this->redirectToRoute("organize_users", array('slug'=>$slug));

    }
    /**
     * @Route("/{_locale}/organization/{slug}/users/remove/{id}",name="organize_users_remove")
     */
    public function removeOrganizationUserAction(Request $request,$slug=null,$id=null){


        $params = array();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $em = $this->getDoctrine()->getEntityManager();

        if (isset($slug) && isset($id)) {

            $organizationEntity = $em->getRepository("BrainStrategistKernelBundle:Organization");

            $user = $request->request->get('user');
            $userEntity = $em->getRepository("BrainStrategistKernelBundle:User");
            $user_to_remove = $userEntity->find($id);

            if ($organizationEntity->isMyOrganization($slug, $this->currentUser->getId()) && $organizationEntity->isMyOrganization($slug, $id)) {

                $organization = $organizationEntity->findOneBySlug($slug);

                $user_to_remove->removeOrganization($organization);
                $organization->removeUsersOrganization($user_to_remove);

                $em->persist($organization);
                $em->persist($user_to_remove);

                /**
                 * remove all Project for the removed user
                 */
                $projectsEntity= $em->getRepository("BrainStrategistProjectBundle:Project");
                $params = array(
                    "organizationID" => $organization->getId(),
                    "userID" => $this->currentUser->getId(),
                    "limit"=>1000,
                    "offset"=>0
                );

                $projects = $projectsEntity->getProjectsByOrganizationObj($params);

                foreach($projects as $project){
                    $project->removeUsersProject($user_to_remove);
                    $user_to_remove->removeProject($project);
                    $em->persist($project);
                    $em->persist($user_to_remove);
                }

                $em->flush();

            }

        }

        return $this->redirectToRoute("organize_users", array('slug'=>$slug));

    }
}

?>