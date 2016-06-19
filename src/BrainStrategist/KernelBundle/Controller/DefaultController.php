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
	    $breadcrumbs->addItem( $this->get('translator')->trans("Home"), $this->get("router")->generate("kernel"));
	    $breadcrumbs->addItem($this->get('translator')->trans("Some text without link"));


        return $this->render('BrainStrategistKernelBundle:Default:index.html.twig');
    }
}
