<?php

namespace Application\Entity;

use Application\Entity\ComponentAbstract;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="facebook")
 */
class Facebook extends ComponentAbstract {

    /**
     * @var string
     * @ORM\Column(name="html", type="text")
     */
    private $html;

    /**
     * @var string
     * @ORM\Column(name="token", type="text")
     */
    private $token;

    public function __construct($html, $token) {
        $this->html = $html;
        $this->token = $token;
    }

    public function setHtml($html) {
        $this->html = $html;
    }

    public function getHtml() {
        return $this->html;
    }

    public function getToken() {
        return $this->token;
    }


    public function setToken($token) {
        $this->token = $token;
    }

}