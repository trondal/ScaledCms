<?php

namespace Scc\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="node")
 */
class Node {

    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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

    /**
     * This var contains an instance of $this->nodesStrategy.
     * The instance is loaded with a PostLoad event listener and will not be persisted by Doctrine.
     */
    protected $component;

    public function __construct(\Scc\Entity\ComponentAbstract $component) {
	$this->component = $component;
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

    public function getComponent() {
	return $this->component;
    }

    public function setComponent(\Scc\Entity\ComponentAbstract $component) {
	$this->component = $component;
	$this->className = $component->getClassName();
	$component->setNode($this);
    }

}