<?php

namespace Scc\Service;

use Doctrine\ORM\EntityManager;
use Scc\Controller\EntityManagerAware;
use Scc\Entity\Node;

class NodeService implements EntityManagerAware {

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function setEntityManager(EntityManager $em) {
	$this->em = $em;
    }

    public function findByNode(Node $node) {
	$repo = $this->em->getRepository('Scc\Entity\Node');
	return $repo->getComponent($node);
    }

}