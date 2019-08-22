<?php

namespace Nines\UtilBundle\EventListener;

use Nines\UtilBundle\Entity\AbstractTerm;
use Nines\UtilBundle\Entity\ContentEntityInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Nines\UtilBundle\Services\Text;
use Psr\Log\LoggerInterface;

class TermNameListener {

    /**
     * @var Text
     */
    private $text;

    private $logger;

    public function __construct(Text $text, LoggerInterface $logger) {
        $this->text = $text;
        $this->logger = $logger;
    }

    public function prePersist(LifecycleEventArgs $args) {
        $this->generateSlug($args->getEntity());
    }

    public function preUpdate(PreUpdateEventArgs $args) {
        $this->generateSlug($args->getEntity());
    }

    private function generateSlug($entity) {
        if (!$entity instanceof AbstractTerm) {
            return;
        }
        if($entity->getName()) {
            return;
        }
        $label = $entity->getLabel();
        $slug = $this->text->slug($label);
        $entity->setName($slug);
    }

}
