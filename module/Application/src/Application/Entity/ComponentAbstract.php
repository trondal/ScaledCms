<?php

namespace Application\Entity;

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
     * @ORM\OneToOne(targetEntity="Node")
     */
    private $node;

    /**
     * Short notation of instance classname, eg. 'Facebook'
     * @var string
     */
    protected $className;

    public function setNode(Node $node) {
	$this->node = $node;
    }

    public function getClassName() {
	$array = explode('\\', get_class($this));
	return $array[2];
    }

}