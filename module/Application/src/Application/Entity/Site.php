<?php

namespace Application\Entity;

use Application\Entity\Page;
use Application\Entity\Site;
use Application\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;
    /**
     * @var string
     * @ORM\Column(name="slug", type="string", length=100, nullable=true)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="sites")
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="site")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $pages;

    public function __construct($name, $slug = null) {
        $this->name = $name;
        $this->slug = $slug;
        $this->pages = new ArrayCollection();
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
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
     * @return Collection
     */
    public function getPages() {
        return $this->pages;
    }

    public function setUser(User $user) {
        $this->user = $user;
    }

}