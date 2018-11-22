<?php

namespace Nines\FeedbackBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nines\BlogBundle\DataFixtures\ORM\LoadPage;
use Nines\BlogBundle\Entity\Page;
use Nines\FeedbackBundle\Entity\Comment;

/**
 * Load some users for unit tests.
 */
class LoadComment extends Fixture implements DependentFixtureInterface {

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) {

        $comment = new Comment();
        $comment->setFullname('Bobby');
        $comment->setFollowUp(false);
        $comment->setContent("Comment 1");
        $comment->setEmail('bob@example.com');
        $comment->setTitle("Title 1");
        $comment->setEntity(Page::class . ':' . $this->getReference('page.published')->getId());
        $comment->setStatus($this->getReference('comment.status.submitted'));
        $em->persist($comment);
        $this->setReference('comment.1', $comment);
        $em->flush();
    }

    public function getDependencies() {
        return array(
            LoadCommentStatus::class,
            LoadPage::class,
        );
    }

}
