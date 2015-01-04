<?php
namespace Landmarx\Bundle\CoreBundle\Menu;

use \Symfony\Component\HttpFoundation\Request;

class MenuBuilder extends \Landmarx\Bundle\CoreBundle\Menu\BaseBuilder
{
    /**
     * Navigation menu for a non auth'd user (guest)
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @return MenuItem $menu
     */
    public function createNonAuthMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav pull-right');

        $menu->addChild(
            'home',
            array('route' =>  'homepage')
        );

        $menu->addChild(
            'sign in',
            array('route' =>  'fos_user_security_login')
        );

        $menu->addChild(
            'sign up',
            array('route' =>  'fos_user_registration_register')
        );

        $menu->addChild(
            'about',
            array('route' => 'about')
        );

        $menu->addChild(
            'contact',
            array('route' => 'contact')
        );

        return $menu;
    }

    /**
     * Navigation root menu for auth'd user
     *
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @return MenuItem $menu
     */
    public function createMainMenu(Request $request)
    {
        if ($this->securityContext->isGranted('ROLE_AUTHENTICATED_ANONYMOUSLY') ||
            !$this->securityContext->isGranted('ROLE_USER')) {
            return $this->createNonauthMenu($request);
        }

        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav pull-right');

        $menu->addChild(
            'home',
            array('route' => 'homepage')
        );

        if ($this->securityContext->isGranted('ROLE_ADMIN')) {
            /////////////////////////////////////////////////////////
            ///ADMIN MENU////////////////////////////////////////////
            /////////////////////////////////////////////////////////
             
        } elseif ($this->securityContext->isGranted('ROLE_USER')) {
            // user custom menu options here.
        }

        $menu->addChild($this->profileMenu($request));
        
        /////////////////////////////////////////////////////////
        ////AUTH MENU////////////////////////////////////////////
        /////////////////////////////////////////////////////////
        if ($this->securityContext->isGranted('ROLE_USER')) {
            $menu->addChild(
                'sign out',
                array('route' => 'fos_user_security_logout')
            );
        } else {
            $menu->addChild(
                'sign up',
                array('route' => 'fos_user_register_index')
            );

            $menu->addChild(
                'sign in',
                array('route' => 'fos_user_security_login')
            );
        }

        return $menu;
    }

    /**
     * Profile menu
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return MenuItem
     */
    public function profileMenu(Request $request)
    {
        $menu = $this->factory->createItem('profile')
            ->setAttribute('dropdown', true)
            ->setAttribute('icon', 'icon-user');

        $menu->addChild(
            'view profile',
            array(
                'route' => 'fos_user_profile_show',
                'routeParameters' => array(
                    'slug' => $this->securityContext->getSlug()
                )
            )
        );

        $menu->addChild(
            'edit profile',
            array(
                'route' => 'fos_user_profile_edit',
                'routeParameters' => array(
                    'slug' => $this->securityContext->getSlug()
                )
            )
        );

        $menu->addChild(
            'change password',
            array('route' => 'fos_user_change_password')
        );
        
        return $menu;
    }
}
