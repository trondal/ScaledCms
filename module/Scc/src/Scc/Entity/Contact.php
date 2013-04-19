<?php

namespace Scc\Entity;

use Scc\Entity\ComponentAbstract;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="contact")
 */
class Contact extends ComponentAbstract implements ComponentAware {

    /**
     * @var string
     * @ORM\Column(name="title", type="text")
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="recipient", type="text")
     */
    private $recipient;

    /**
     * @var string
     * @ORM\Column(name="msg", type="text")
     */
    private $msg;

    public function __construct($title, $recipient, $msg) {
	$this->title = $title;
	$this->recipient = $recipient;
	$this->msg = $msg;
    }

    public function setTitle($title) {
	$this->title = $title;
    }

    public function getTitle() {
	return $this->title;
    }

    public function getRecipient() {
	return $this->recipient;
    }

    public function setRecipient($recipient) {
	$this->recipient = $recipient;
    }

    public function setMsg($msg) {
	$this->msg = $msg;
    }

    public function getMsg(){
	return $this->msg;
    }

}