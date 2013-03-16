<?php

namespace Scc\Entity;

use Scc\Entity\ComponentAbstract;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="twitter")
 */
class Twitter extends ComponentAbstract {

    /**
     * @var string
     * @ORM\Column(name="html", type="text")
     */
    private $html;

    public function __construct($html) {
	$this->html = $html;
    }

    public function setHtml($html) {
	$this->html = $html;
    }

    public function getHtml() {
	return $this->html;
    }

}