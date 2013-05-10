<?php

namespace Scc\Service;

use Doctrine\ORM\EntityManager;
use Scc\Controller\EntityManagerAware;
use Scc\Entity\Node;

class ContactService implements EntityManagerAware {

    protected $em;

    public function setEntityManager(EntityManager $em) {
        $this->em = $em;
    }

    public function findOneByNode(Node $node) {
        $repo = $this->em->getRepository('Scc\Entity\Contact');
        return $repo->findOneByNode($node);
    }
}