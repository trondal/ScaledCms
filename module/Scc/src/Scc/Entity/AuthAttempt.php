<?php

namespace Scc\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Table(name="auth_attempt")
 * @ORM\Entity
 */
class AuthAttempt {

    /**
     *
     * @var integer $id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var \Scc\Entity\User
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $user;

    /**
     * @ORM\Column(type="datetime", name="created", nullable=false)
     * @var string timestamp in UTC
     */
    protected $time;

    /**
     * @see Zend\Authentication\Result
     * @ORM\Column(type="smallint", name="status", nullable=false)
     * @var integer
     */
    protected $status;

    /**
     * @ORM\Column(type="string", name="ip", length=45, nullable=true)
     * @var string
     */
    protected $ip;

    public function __construct($user, $time, $status, $ip) {
        $this->user = $user;
        $this->setTime($time);
        $this->setStatus($status);
        $this->setIp($ip);
    }
    
    public function getId() {
        return $this->id;
    }

    public function getTime() {
        return $this->time;
    }

    public function setTime($time) {
        $this->time = $time;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getIp() {
        return $this->ip;
    }

    public function setIp($ip) {
        $this->ip = $ip;
    }

}