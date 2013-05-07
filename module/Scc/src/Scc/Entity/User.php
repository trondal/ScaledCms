<?php

namespace Scc\Entity;

use Scc\Entity\Site;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User {

    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=32, nullable=false)
     */
    private $userName;

    /**
     * @var string
     * @ORM\Column(type="string", length=32, nullable=false)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=60, nullable=false)
     */
    private $password;

    /**
     * @var Site[]
     * @ORM\OneToMany(targetEntity="Site", mappedBy="user")
     */
    private $sites;

    public function __construct($userName, $password, $email) {
	$this->userName = $userName;
	$this->password = $password;
	$this->email = $email;
	$this->sites = new ArrayCollection();
    }

    public function getId() {
	return $this->id;
    }

    public function getUserName() {
	return $this->userName;
    }

    public function setUserName($username) {
	$this->userName = $username;
    }

    public function getEmail() {
	return $this->email;
    }

    public function setEmail($email) {
	$this->email = $email;
    }

    public function getPassword() {
	return $this->password;
    }

    public function setPassword($password) {
	$this->password = $password;
    }

    public function addSite(Site $site) {
	$this->sites[] = $site;
	$site->setUser($this);
    }

    /**
     *
     * @return Collection
     */
    public function getSites() {
	return $this->sites;
    }

    public function removeSites(Collection $sites) {
	foreach ($sites as $site) {
	    $site->setUser(null);
	    $this->sites->removeElement($site);
	}
    }

}