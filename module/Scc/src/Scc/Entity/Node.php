<?php

namespace Scc\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="\Scc\Entity\Repository\NodeRepository")
 * @ORM\Table(name="node")
 * @Gedmo\Tree(type="nested")
 */
class Node {

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Node", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Node", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Scc\Entity\Page", inversedBy="nodes")
     */
    protected $page;

    /**
     * This var contains the name of the Node
     * that is used for this node item.
     *
     * @var string
     * @ORM\Column(type="string", name="classname", length=64, nullable=false )
     */
    protected $className;

    public function __construct(\Scc\Entity\ComponentAbstract $component) {
	$this->className = $component->getClassName();
	$component->setNode($this);
    }

    public function getId() {
	return $this->id;
    }

    public function setPage(Page $page = null) {
	$this->page = $page;
    }

    public function getPage() {
	return $this->page;
    }

    public function getClassName() {
	return $this->className;
    }

    public function setParent(Node $parent = null) {
	$this->parent = $parent;
    }

    public function getParent() {
	return $this->parent;
    }

}