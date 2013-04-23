<?php

namespace Scc\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Scc\Entity\Page;
use Scc\Entity\Site;
use Scc\Entity\User;
use Scc\Entity\HostName;

/**
 * @ORM\Entity
 * @ORM\Table(name="site", uniqueConstraints={@ORM\UniqueConstraint(name="search_idx", columns={"title", "user_id"})})
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
     * @ORM\Column(name="title", type="string", length=100, nullable=true)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="sites")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="site")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $pages;
    
    /**
     * @ORM\OneToMany(targetEntity="HostName", mappedBy="site")
     **/
    private $hostNames;

    public function __construct($title, $slug = null) {
	$this->title = $title;
	$this->slug = $slug;
	$this->pages = new ArrayCollection();
        $this->hostNames = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }
    
    public function getTitle() {
	return $this->title;
    }

    public function setTitle($title) {
	$this->title = $title;
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

    public function addHostName(\Scc\Entity\HostName $hostName) {
        $hostName->addSite($this);
        $this->hostNames[] = $hostName;
    }
    
}