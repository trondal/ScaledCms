<?php

namespace Scc\Service;

use Doctrine\ORM\EntityManager;
use Scc\Controller\EntityManagerAware;

class ContactService implements EntityManagerAware {
    
    protected $em;
    
    public function setEntityManager(EntityManager $em) {
        $this->em = $em;
    }
    
    public function findOneByNode(\Scc\Entity\Node $node) {
        $repo = $this->em->getRepository('Scc\Entity\Contact');
        return $repo->findOneByNode($node);
    }
}