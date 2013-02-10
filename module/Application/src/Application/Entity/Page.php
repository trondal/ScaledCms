<?php

namespace Application\Entity;

use Application\Entity\Node;
use Application\Entity\Page;
use Application\Entity\Site;
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

    /*Nodeon\Entity\Node[]
     * @ORM\OneToMany(targetEntity="Application\Entity\Node", mappedBy="page")
     */
    protected $nodes;

    /**
     * @var \Entity\Site
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="pages")
     */
    private $site;

    public function __construct($title) {
        $this->title = $title;
        $this->children = new ArrayCollection();
        $this->nodes = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function setId() {
        $this->id = null;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
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
        return $this;
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