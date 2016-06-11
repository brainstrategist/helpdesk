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

        $menu->addChild('Home', array('route' => 'kernel'));
        $menu->addChild('The project', array('route' => 'kernel'));

        $menu['The project']->addChild('Discover', array('route' => 'kernel'));

        return $menu;
    }

    public function userMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');

        $menu->addChild('User', array('label' => 'Hi visitor'))
            ->setAttribute('dropdown', true)
            ->setAttribute('icon', 'fa fa-user');
        $menu['User']->addChild('Edit profile', array('route' => 'acme_hello_profile'))
            ->setAttribute('icon', 'fa fa-edit');
        return $menu;
    }
}