<?php
namespace BrainStrategist\KernelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SearchController extends Controller
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
     * @Route("/{_locale}/search", name="search")
     */
    public function searchAction(Request $request)
    {

        if ('POST' == $request->getMethod()) {
            $searchField = $request->request->get('search-box');

            if($searchField!=""){
                $params=array();
                $em = $this->getDoctrine()->getManager();
                $ticketEntity = $em->getRepository("BrainStrategistProjectBundle:Ticket");

                $params = array(
                    "userID" => $this->currentUser->getId(),
                    "search_field" => $searchField);

                $search_query = $ticketEntity->searchTicketBySearchField($params);

                // pagination
                if(null !== $request->query->getInt('page') && !isset($page)){
                    $page = 1;
                }
                // nb results per page
                if(null !== $request->query->getInt('limit') && !isset($limit)){
                    $limit = 10;
                }

                $paginator  = $this->get('knp_paginator');
                $tickets = $paginator->paginate(
                    $search_query,
                    $page,
                    $limit
                );
                return $this->render('BrainStrategistKernelBundle:Default:search.html.twig',
                    array(
                        "search_result" => $tickets
                    ));

            }else{
                throw new HttpException(400, "Please enter a search first :(");
            }

        }



    }
}