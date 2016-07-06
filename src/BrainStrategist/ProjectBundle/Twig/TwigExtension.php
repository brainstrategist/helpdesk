<<<<<<< HEAD
<?php

namespace BrainStrategist\ProjectBundle\Twig;

class TwigExtension extends \Twig_Extension
{

    /**
     * Return the functions registered as twig extensions
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new  \Twig_SimpleFunction('sub_date', array($this, 'sub_date')),
        );
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
=======
<?php

namespace BrainStrategist\ProjectBundle\Twig;

class TwigExtension extends \Twig_Extension
{

    /**
     * Return the functions registered as twig extensions
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new  \Twig_SimpleFunction('sub_date', array($this, 'sub_date')),
        );
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
>>>>>>> 001f67bbc45d26abf82c92127ab54b3791b990ea
?>