<?php
namespace BrainStrategist\KernelBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function userUnregisteredMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');
        $menu->addChild('Login', array('route' => 'fos_user_security_login', 'label' => '<i class="fa fa-user"></i> Connection ', 'extras' => array('safe_label' => true)));
        $menu->addChild('Facebook Connect', array('uri' => '/' . $options['locale'] . '/connect/facebook', 'label' => '<i class="fa fa-facebook"></i> Connect with Facebook ', 'extras' => array('safe_label' => true)));
        $menu->addChild('Register', array('route' => 'fos_user_registration_register', 'label' => '<i class="fa fa-plus"></i> Register ', 'extras' => array('safe_label' => true)));
        return $menu;
    }

    public function userRegisterdMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');

        $menu->addChild('User', array('route' => 'fos_user_profile_edit', 'label' => 'Hello ' . $options['username'] . '  <img src="' . $options['avatar'] . '" class="img-circle avatar_top" alt="User Image" />', 'extras' => array('safe_label' => true)))
            ->setAttribute('dropdown', true)
            ->setAttribute('icon', 'fa fa-user')
            ->setChildrenAttribute('class', 'dropdown-menu');
        $menu['User']->addChild('Edit profile', array('route' => 'fos_user_profile_edit'))
            ->setAttribute('icon', 'fa fa-edit');

        $menu->addChild('Dashboard', array('route' => 'dashboard_access'))
            ->setAttribute('icon', 'fa fa-edit');

        $menu->addChild('Disconnect', array('route' => 'fos_user_security_logout'))
            ->setAttribute('icon', 'fa fa-edit');
        return $menu;
    }

    public function ProjectDashboardMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-pills nav-stacked');

        $menu->addChild('Issues', array('route' => 'project_access', 'routeParameters' => array('slug' => $options['project_slug'])))
            ->setAttribute('icon', 'fa fa-edit');

        $menu->addChild('Users', array('route' => 'project_view', 'routeParameters' => array('slug' => $options['project_slug'], 'view' => 'users-list')))
            ->setAttribute('icon', 'fa fa-edit');

        $menu->addChild('Create issue', array('route' => 'project_view', 'routeParameters' => array('slug' => $options['project_slug'], 'view' => 'ticket-create')))
            ->setAttribute('icon', 'fa fa-edit');

        return $menu;
    }

    public function UserDashboardMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-pills nav-stacked');

        $menu->addChild('Backlog', array('route' => 'dashboard_access'))
            ->setAttribute('icon', 'fa fa-edit');

        return $menu;
    }
}