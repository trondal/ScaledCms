<?php

namespace Scc\Entity;

use Doctrine\ORM\Mapping as ORM;
use Scc\Entity\ComponentAbstract;

/**
 * @ORM\Entity
 * @ORM\Table(name="panel")
 */
class Panel extends ComponentAbstract implements ContainerAware {

    public function __construct() {
	
    }

}