<?php

namespace Nines\BlogBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Nines\BlogBundle\Entity\PageCategory;

/**
 * Load some users for unit tests.
 */
class LoadPageCategory extends Fixture {

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager) {
        $category = new PageCategory();
        $category->setName('announcement');
        $category->setLabel('Announcement');
        $category->setDescription('Stuff happened.');
        $manager->persist($category);
        $manager->flush();

        $this->addReference('page-category-1', $category);
    }

}
