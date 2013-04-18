<?php

namespace MessageBoard\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use MessageBoard\Entity\Board;
use Scc\Entity\User;

/**
 * @ORM\Entity
 */
class Message {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Board", inversedBy="messages")
     */
    private $board;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $sender;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var datetime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    public function __construct($sender, $content, $title = null) {
	$this->sender = $sender;
	$this->content = $content;
	$this->title = $title;
    }

    public function setBoard(Board $board) {
	$this->board = $board;
    }

    public function getBoard() {
	return $this->board;
    }

    public function setTitle($title) {
	$this->title = $title;
    }

    public function getTitle() {
	return $this->title;
    }

    public function setContent($content) {
	$this->content = $content;
    }

    public function getContent() {
	return $this->content;
    }

    public function setSender($sender) {
	$this->sender = $sender;
    }

    public function getSender() {
	return $this->sender;
    }

    public function getCreated() {
	return $this->created;
    }

    public function getUpdated() {
	return $this->updated;
    }

}