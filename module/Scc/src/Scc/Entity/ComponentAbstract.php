<?php

namespace Scc\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class ComponentAbstract {

    /**
     * @var integer
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Node
     * @ORM\ManyToOne(targetEntity="Node", cascade={"persist"})
     */
    private $node;

    public function getId() {
	return $this->id;
    }

    public function setNode(Node $node) {
	$this->node = $node;
    }

    public function getClassName() {
	return get_class($this);
    }

    public function getNsPrefix() {
	$result = explode('\\', get_class($this));
	return $result[0];
    }

    public function getNsSuffix() {
	$result = explode('\\', get_class($this));
	return $result[count($result) - 1];
    }

}