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
     * @ORM\ManyToOne(targetEntity="Node")
     */
    private $node;

    public function setNode(Node $node) {
	$this->node = $node;
    }

    public function getClassName() {
	$array = explode('\\', get_class($this));
	return $array[2];
    }

}