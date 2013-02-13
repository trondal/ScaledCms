<?php

namespace Application\Entity;

use Application\Entity\Site;
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
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=32, nullable=false)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=32, nullable=false)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=32, nullable=false)
     */
    private $password;

    /**
     * @var Site[]
     * @ORM\OneToMany(targetEntity="Site", mappedBy="user")
     */
    private $sites;

    public function __construct($name, $password, $email) {
        $this->name = $name;
        $this->password = $password;
        $this->email = $email;
        $this->sites = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
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

    public function removeSites(Collection $sites){
        foreach($sites as $site){
            $site->setUser(null);
            $this->sites->removeElement($site);
        }
    }

}