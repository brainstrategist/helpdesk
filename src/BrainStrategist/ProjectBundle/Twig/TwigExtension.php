<?php

namespace BrainStrategist\ProjectBundle\Twig;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TwigExtension extends \Twig_Extension
{
    protected $em;
    private $context;

    public function __construct($em,TokenStorageInterface $context)
    {
        $this->em = $em;
        $this->context = $context;
    }

    public function getUser()
    {
        return $this->context->getToken()->getUser();
    }

    /**
     * Return the functions registered as twig extensions
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new  \Twig_SimpleFunction('sub_date', array($this, 'sub_date')),
            new  \Twig_SimpleFunction('listing_projects', array($this, 'listing_projects')),
        );
    }

    public function listing_projects()
    {
        $repository = $this->em->getRepository('BrainStrategistProjectBundle:Project');
        return $repository->findMyProjects(array("userID" => $this->getUser()->getId(),'limit'=>100,'offset'=>0));
    }

    public function sub_date($dateStart, $dateEnd)
    {


        $dateStart_DateTime = new \DateTime($dateStart);
        $dateEnd_DateTime = new \DateTime($dateEnd);

        $interval = $dateEnd_DateTime->diff($dateStart_DateTime);
        if($interval->format('%R%a')>0){
            return   $interval->format('%R%a days %H:%I:%S');
        }else{
            return   $interval->format('%H:%I:%S');
        }


    }
    public function getName()
    {
        return 'sub_date';
    }
}
?>