<?php

namespace Scc\Entity;

use Scc\Entity\Node;
use Scc\Entity\Page;
use Scc\Entity\Site;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 * @ORM\Table(name="page")
 */
class Page {

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
    private $title;

    /**
     * @var string
     * @ORM\Column(name="slug", type="string", length=100, nullable=true)
     */
    private $slug;

    /**
     * @Gedmo\TreeLeft
     * @var int
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeRight
     * @var int
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(type="integer")
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="children")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    private $parent;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $level;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="parent", cascade={"persist"})
     * @ORM\OrderBy{"left" = "ASC}
     */
    private $children;

    /**
     * @var Scc\Entity\Node[]
     * @ORM\OneToMany(targetEntity="Scc\Entity\Node", mappedBy="page")
     * @ORM\OrderBy{"left" = ASC}
     */
    protected $nodes;

    /**
     * @var \Entity\Site
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="pages")
     */
    private $site;

    public function __construct($title, $slug = null) {
	$this->title = $title;
	$this->slug = $slug;
	$this->children = new ArrayCollection();
	$this->nodes = new ArrayCollection();
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

    public function setSlug($slug) {
	$this->slug = $slug;
    }

    public function getSlug() {
	return $this->slug;
    }

    /**
     * Set parent.
     *
     * @param Page $parent
     * @return void
     */
    public function setParent(Page $parent = null) {
	$this->parent = $parent;
    }

    public function getChildren() {
	return $this->children;
    }

    public function getLeft() {
	return $this->lft;
    }

    public function getRight() {
	return $this->rgt;
    }

    public function cloneChildren() {
	$children = $this->getChildren();
	$this->children = new ArrayCollection();
	foreach ($children as $child) {
	    $clonedChild = clone $child;
	    $clonedChild->cloneChildren();
	    $this->children->add($clonedChild);
	    $clonedChild->setParent($this);
	}
    }

    public function addNode(Node $node) {
	$this->nodes[] = $node;
	$node->setPage($this);
    }

    public function getNodes() {
	return $this->nodes;
    }

    /**
     * Set site.
     *
     * @param Site $site
     * @return Page
     */
    public function setSite(Site $site) {
	$this->site = $site;
    }

    /**
     * Retrieve site.
     *
     * @return Site
     */
    public function getSite() {
	return $this->site;
    }

}