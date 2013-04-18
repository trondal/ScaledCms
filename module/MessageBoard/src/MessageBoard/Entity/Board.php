<?php

namespace MessageBoard\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Scc\Entity\ComponentAbstract;
use Scc\Entity\ComponentAware;

/**
 * @ORM\Entity
 */
class Board extends ComponentAbstract implements ComponentAware {

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="\MessageBoard\Entity\Message", mappedBy="board")
     * @ORM\OrderBy({"created" = "ASC"})
     * */
    private $messages;

    public function __construct($title) {
	$this->title = $title;
	$this->messages = new ArrayCollection();
    }

    public function getMessages() {
	return $this->messages;
    }

    //message is owning side
    public function addMessage(\MessageBoard\Entity\Message $message) {
	$message->setBoard($this);
	$this->messages[] = $message;
    }

    public function setTitle($title) {
	$this->title = $title;
    }

    public function getTitle() {
	return $this->title;
    }

    /**
     * @param Collection $messages
     */
    public function addMessages(Collection $messages) {
	foreach ($messages as $message) {
	    $message->setBoard($this);
	    $this->messages->add($message);
	}
    }

    /**
     * @param Collection $messages
     */
    public function removeMessages(Collection $messages) {
	foreach ($messages as $message) {
	    $message->setBoard(null);
	    $this->messages->removeElement($message);
	}
    }

}