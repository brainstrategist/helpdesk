<?php

namespace BrainStrategist\KernelBundle\Twig;

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
            new  \Twig_SimpleFunction('file_exists', array($this, 'file_exists')),
        );
    }

    public function file_exists($filename)
    {
        if(is_file($filename) && file_exists($filename)){
            return true;
        }
        return false;
    }
    public function getName()
    {
        return 'file_exists';
    }
}
?>