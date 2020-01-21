<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace Nines\UtilBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * AbstractEntity adds id, created, and updated fields along with the
 * normal getters. And it sets up automatic callbacks to set the created
 * and updated DateTimes.
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class AbstractEntity {
    /**
     * The entity's ID.
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"shallow"})
     */
    protected $id;

    /**
     * The DateTime the entity was created (persisted really).
     *
     * @var DateTime
     * @ORM\Column(type="datetime")
     * @Groups({"shallow"})
     */
    protected $created;

    /**
     * The DateTime the entity was last updated.
     *
     * @var DateTime
     * @ORM\Column(type="datetime")
     * @Groups({"shallow"})
     */
    protected $updated;

    /**
     * Constructor. Does nothing. Exists incase a subclass accidentally calls
     * parent::__construct().
     */
    public function __construct() {
    }

    /**
     * Force all entities to provide a stringify function.
     *
     * @return string
     */
    abstract public function __toString();

    /**
     * Get the ID.
     */
    public function getId() : int {
        return $this->id;
    }

    /**
     * Does nothing. Setting the created timestamp happens automatically. Exists
     * to prevent a subclass accidentally setting a timestamp.
     */
    public function setCreated(DateTime $created) : void {
    }

    /**
     * Get the created timestamp.
     */
    public function getCreated() : DateTime {
        return $this->created;
    }

    /**
     * Does nothing. Setting the updated timestamp happens automatically.
     */
    public function setUpdated(DateTime $updated) : void {
    }

    /**
     * Get the updated timestamp.
     */
    public function getUpdated() : DateTime {
        return $this->updated;
    }

    /**
     * Sets the created and updated timestamps. This method should be
     * private or protected, but that interferes with the life cycle callbacks.
     *
     * @ORM\PrePersist()
     */
    final public function prePersist() : void {
        $this->created = new DateTime();
        $this->updated = new DateTime();
    }

    /**
     * Sets the updated timestamp.
     *
     * @ORM\PreUpdate()
     */
    final public function preUpdate() : void {
        $this->updated = new DateTime();
    }
}
