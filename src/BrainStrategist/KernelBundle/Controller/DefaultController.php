<?php

namespace BrainStrategist\KernelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/{_locale}/", name="kernel")
     * @Route("/", name="default")
     */
    public function indexAction(Request $request)
    {
	    $breadcrumbs = $this->get("white_october_breadcrumbs");
	    // Simple example
	    $breadcrumbs->addItem( $this->get('translator')->trans("Home"), $this->get("router")->generate("kernel"));

	    // Example without URL
	    $breadcrumbs->addItem($this->get('translator')->trans("Some text without link"));

        $array_sample_pagination =array(
                                    array(
                                        "id" => "1",
                                        "title" => "My title",
                                        "date" => "2016-12-02",
                                        "time" => "22:22:22"
                                    ),
                                    array(
                                        "id" => "2",
                                        "title" => "My title",
                                        "date" => "2016-12-02",
                                        "time" => "22:22:22"
                                    ),
                                    array(
                                        "id" => "3",
                                        "title" => "My title",
                                        "date" => "2016-12-02",
                                        "time" => "22:22:22"
                                    ),
                                    array(
                                        "id" => "4",
                                        "title" => "My title",
                                        "date" => "2016-12-02",
                                        "time" => "22:22:22"
                                    ),
                                    array(
                                        "id" => "5",
                                        "title" => "My title",
                                        "date" => "2016-12-02",
                                        "time" => "22:22:22"
                                    ),            
                                    array(
                                        "id" => "6",
                                        "title" => "My title",
                                        "date" => "2016-12-02",
                                        "time" => "22:22:22"
                                    ),            
                                    array(
                                        "id" => "7",
                                        "title" => "My title",
                                        "date" => "2016-12-02",
                                        "time" => "22:22:22"
                                    ),            
                                    array(
                                        "id" => "8",
                                        "title" => "My title",
                                        "date" => "2016-12-02",
                                        "time" => "22:22:22"
                                    ),            
                                    array(
                                        "id" => "9",
                                        "title" => "My title",
                                        "date" => "2016-12-02",
                                        "time" => "22:22:22"
                                    ),            
                                    array(
                                        "id" => "10",
                                        "title" => "My title",
                                        "date" => "2016-12-02",
                                        "time" => "22:22:22"
                                    ),            
                                    array(
                                        "id" => "11",
                                        "title" => "My title",
                                        "date" => "2016-12-02",
                                        "time" => "22:22:22"
                                    ),            
                                    array(
                                        "id" => "12",
                                        "title" => "My title",
                                        "date" => "2016-12-02",
                                        "time" => "22:22:22"
                                    ),
                                );

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $array_sample_pagination, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        return $this->render('BrainStrategistKernelBundle:Default:index.html.twig', array('pagination' => $pagination));
    }
}
