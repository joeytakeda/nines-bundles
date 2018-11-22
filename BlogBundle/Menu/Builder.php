<?php

namespace Nines\BlogBundle\Menu;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class to build some menus for navigation.
 */
class Builder implements ContainerAwareInterface {

    use ContainerAwareTrait;

    const CARET = ' ▾'; // U+25BE, black down-pointing small triangle.

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authChecker;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     *
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authChecker, TokenStorageInterface $tokenStorage, EntityManagerInterface $em) {
        $this->factory = $factory;
        $this->authChecker = $authChecker;
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
    }

    private function hasRole($role) {
        if (!$this->tokenStorage->getToken()) {
            return false;
        }
        return $this->authChecker->isGranted($role);
    }

    private function getUser() {
        if( ! $this->hasRole('ROLE_USER')) {
            return null;
        }
        return $this->tokenStorage->getToken()->getUser();
    }

    /**
     * Build a menu for blog pages.
     *
     * @param array $options
     * @return ItemInterface
     */
    public function pageNavMenu(array $options) {
        $settings = array_merge([
            'title' => 'About',
        ], $options);
        $root = $this->factory->createItem('root');
        $root->setChildrenAttributes(array(
            'class' => 'nav navbar-nav',
        ));
        $root->setAttribute('dropdown', true);
        $pages = $this->em->getRepository('NinesBlogBundle:Page')->findBy(
            array('public' => true),
            array('weight' => 'ASC','title' => 'ASC')
        );

        $menu = $root->addChild('about', array(
            'uri' => '#',
            'label' => $settings['title'] . self::CARET
        ));
        $menu->setAttribute('dropdown', true);
        $menu->setLinkAttribute('class', 'dropdown-toggle');
        $menu->setLinkAttribute('data-toggle', 'dropdown');
        $menu->setChildrenAttribute('class', 'dropdown-menu');


        foreach ($pages as $page) {
            $menu->addChild($page->getTitle(), array(
                'route' => 'page_show',
                'routeParameters' => array(
                    'id' => $page->getId(),
                )
            ));
        }
        if ($this->hasRole('ROLE_BLOG_ADMIN')) {
            $menu->addChild('divider', array(
                'label' => '',
            ));
            $menu['divider']->setAttributes(array(
                'role' => 'separator',
                'class' => 'divider',
            ));

            $menu->addChild('page_admin', array(
                'label' => 'All Pages',
                'route' => 'page_index',
            ));
        }

        return $root;
    }

}
