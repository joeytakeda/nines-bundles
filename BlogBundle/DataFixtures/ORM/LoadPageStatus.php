<?php

namespace Nines\BlogBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Nines\BlogBundle\Entity\PageStatus;

/**
 * Load some users for unit tests.
 */
class LoadPageStatus extends Fixture {

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager) {
        $draft = new PageStatus();
        $draft->setName('draft');
        $draft->setLabel('Draft');
        $draft->setPublic(false);
        $draft->setDescription('Drafty');
        $manager->persist($draft);
        $this->addReference('page-status-1', $draft);

        $published = new PageStatus();
        $published->setName('published');
        $published->setLabel('Published');
        $published->setPublic(true);
        $published->setDescription('Public');
        $manager->persist($published);
        $this->addReference('page-status-2', $draft);
        $manager->flush();
    }

}
