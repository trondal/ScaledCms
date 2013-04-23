<?php

namespace Scc\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="hostname")
 * @ORM\Entity
 */
class HostName {
    
    /**
     * @var int
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
       
    /**
     * @ORM\ManyToOne(targetEntity="\Scc\Entity\Site", inversedBy="hostNames")
     **/
    private $site;
    
    /**
     * @var string
     * @ORM\Column(name="host", type="string", length=100, nullable=false, unique=true)
     */
    private $hostName;
    
    public function __construct($hostName) {
        $this->hostName = $hostName;
    }
    
    public function setHostName($hostName) {
        $this->hostName = $hostName;
    }
    
    public function getHostName() {
        return $this->hostName;
    }
    
    public function addSite(\Scc\Entity\Site $site) {
        $this->site = $site;
    }
    
}
