<?php

namespace Nines\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nines\UtilBundle\Entity\AbstractTerm;

/**
 * PageCategory
 *
 * @ORM\Table(name="blog_page_category")
 * @ORM\Entity(repositoryClass="Nines\BlogBundle\Repository\PageCategoryRepository")
 */
class PageCategory extends AbstractTerm
{
    /**
     * Pages in the category.
     * @var Collection|Page[]
     * @ORM\OneToMany(targetEntity="Page", mappedBy="category")
     */
    private $pages;

    /**
     * Construct the category.
     */
    public function __construct() {
        parent::__construct();
        $this->pages = new ArrayCollection();
    }

    /**
     * Add page
     *
     * @param Page $page
     *
     * @return PageCategory
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
}
