<?php
namespace BrainStrategist\KernelBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav main-navigation');

        $menu->addChild('Themes', array('route' => 'kernel'));
        $menu->addChild('Daily topic', array('route' => 'kernel'));
        $menu->addChild('The project', array('route' => 'kernel'))
            ->setAttribute('dropdown', true)
             ->setChildrenAttribute('class', 'dropdown-menu');
        $menu['The project']->addChild('Discover', array('route' => 'kernel'));
        $menu['The project']->addChild('How it\'s work ?', array('route' => 'kernel'));
        $menu['The project']->addChild('Who we are ?', array('route' => 'kernel'));

        return $menu;
    }

    public function userMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');

        $menu->addChild('User', array('route' => 'fos_user_profile_edit','label' => 'Hello '.$options['username'].'  <img src="'.$options['avatar'].'" class="img-circle avatar_top" alt="User Image" />','extras' => array('safe_label' => true)))
            ->setAttribute('dropdown', true)
            ->setAttribute('icon', 'fa fa-user')
            ->setChildrenAttribute('class', 'dropdown-menu');
        $menu['User']->addChild('Edit profile', array('route' => 'fos_user_profile_edit'))
            ->setAttribute('icon', 'fa fa-edit');

        $menu->addChild('Disconnect', array('route' => 'fos_user_security_logout'))
            ->setAttribute('icon', 'fa fa-edit');
        return $menu;
    }
}