<?php

namespace Nines\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * PageStatus
 *
 * @ORM\Table(name="blog_page_status")
 * @ORM\Entity(repositoryClass="Nines\BlogBundle\Repository\PageStatusRepository")
 */
class PageStatus extends AbstractTerm
{
    /**
     * True if the status is meant to be public.
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $public;

    /**
     * List of the pages with this status.
     *
     * @var Collection|Page[]
     * @ORM\OneToMany(targetEntity="Page", mappedBy="status")
     */
    private $pages;

    /**
     * Build the page.
     */
    public function __construct() {
        parent::__construct();
        $this->public = false;
        $this->pages = new ArrayCollection();
    }

    /**
     * Add page
     *
     * @param Page $page
     *
     * @return PageStatus
     */
    public function addPage(Page $page)
    {
        $this->pages[] = $page;

        return $this;
    }

    /**
     * Remove page
     *
     * @param Page $page
     */
    public function removePage(Page $page)
    {
        $this->pages->removeElement($page);
    }

    /**
     * Get pages
     *
     * @return Collection
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Set public
     *
     * @param boolean $public
     *
     * @return PageStatus
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }
}
