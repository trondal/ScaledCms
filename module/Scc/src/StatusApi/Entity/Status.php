<?php

namespace StatusApi\Entity;

use Doctrine\ORM\Mapping as ORM;

/** 
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\StatusApi\Entity\Repository\StatusRepository")
 */
class Status implements \StatusApi\StatusInterface {

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=6, nullable=false)
     * @var string
     */
    protected $type;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @var string
     */
    protected $timestamp;

    /**
     * @ORM\Column(type="string",name="`user`", length=256, nullable=false)
     * @var string
     */
    protected $user;

    /**
     * @ORM\Column(type="string", length=256, nullable=true)
     * @var string
     */
    protected $text;

    public function __construct($type = null, $timestamp = null, $user = null, $text = null) {
        $this->type = $type;
        $this->timestamp = $timestamp;
        $this->user = $user;
        $this->text = $text;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getType() {
        return $this->type;
    }

    public function setText($text) {
        $this->text = $text;
    }

    public function getText() {
        return $this->text;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function getUser() {
        return $this->user;
    }

    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
    }

    public function getTimestamp() {
        return $this->timestamp;
    }

}