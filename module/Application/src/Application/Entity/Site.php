<?php

namespace Application\Entity;

use Application\Entity\Page;
use Application\Entity\Site;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="site")
 */
class Site {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $slug;

    /*Pageon\Entity\Page[]
     * @ORM\OneToMany(targetEntity="Page", mappedBy="site")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $pages;

    public function __construct($slug) {
        $this->slug = $slug;
        $this->pages = new ArrayCollection();
    }

    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }

    /**
     * Add page.
     *
     * @param Page $page
     * @return Site
     */
    public function addPage(Page $page) {
        $this->pages[] = $page;
        $page->setSite($this);
    }

    /**
     * Retrieve pages.
     *
     * @return array
     */
    public function getPages() {
        return $this->pages;
    }

}