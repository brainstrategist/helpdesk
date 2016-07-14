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
        $menu->addChild('Facebook Connect', array('uri' => '/' . $options['locale'] . '/connect/facebook', 'label' => '<i class="fa fa-facebook"></i> Connect with Facebook ', 'extras' => array('safe_label' => true)));
        $menu->addChild('Register', array('route' => 'fos_user_registration_register', 'label' => '<i class="fa fa-plus"></i> Register ', 'extras' => array('safe_label' => true)));
        return $menu;
    }

    public function userRegisterdMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');
        $menu->addChild('User', array('route' => 'fos_user_profile_edit', 'label' => '<span class="expended">Hello ' . $options['username'] . '</span>  <img src="' . $options['avatar'] . '" class="img-circle avatar_top" alt="User Image" />', 'extras' => array('safe_label' => true)))
            ->setAttribute('dropdown', true)
            ->setAttribute('icon', 'fa fa-user')
            ->setChildrenAttribute('class', 'dropdown-menu');
        $menu['User']->addChild('Edit profile', array('route' => 'fos_user_profile_edit'));
        $menu->addChild('Dashboard', array('route' => 'dashboard_access','label' => '<i class="fa fa-tachometer"></i> <span class="expended">Dashboard</span>','extras' => array('safe_label' => true)));
        $menu->addChild('Disconnect', array('route' => 'fos_user_security_logout','label' => '<i class="fa fa-sign-out"></i> <span class="expended">Sign out</span>','extras' => array('safe_label' => true)));
        return $menu;
    }

    public function ProjectDashboardMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-pills nav-stacked');
        $menu->addChild('Issues', array('route' => 'project_access', 'routeParameters' => array('slug' => $options['project_slug']),'label' => '<i class="fa fa-bug"></i> Issues ','extras' => array('safe_label' => true)));
        $menu->addChild('Users', array('route' => 'project_view', 'routeParameters' => array('slug' => $options['project_slug'], 'view' => 'users-list'),'label' => '<i class="fa fa-users"></i> Members ','extras' => array('safe_label' => true)));
        $menu->addChild('Create issue', array('route' => 'project_view', 'routeParameters' => array('slug' => $options['project_slug'], 'view' => 'ticket-create'),'label' => '<i class="fa fa-plus-square"></i> Create issue ','extras' => array('safe_label' => true)));

        return $menu;
    }

    public function UserDashboardMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-pills nav-stacked');
        $menu->addChild('Backlog', array('route' => 'dashboard_access','label' => '<i class="fa fa-bars"></i> Backlog ','extras' => array('safe_label' => true)));
        $menu->addChild('Kanban', array('route' => 'dashboard_kanban', 'routeParameters' => array('viewtype' => 'kanban'),'label' => '<i class="fa fa-list"></i> Kanban ','extras' => array('safe_label' => true)));

        return $menu;
    }
}