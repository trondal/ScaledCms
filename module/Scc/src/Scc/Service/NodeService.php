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

    public function find($id) {
        $repo = $this->em->getRepository('Scc\Entity\Node');
        return $repo->find($id);
    }

    public function findComponentByNode(Node $node) {
	$repo = $this->em->getRepository('Scc\Entity\Node');
	return $repo->getComponent($node);
    }

    public function getChildren($node = null, $direct = false, $sortByField = null, $direction = 'ASC', $includeNode = false) {
        $repo = $this->em->getRepository('Scc\Entity\Node');
        return $repo->getChildren($node, $direct, $sortByField, $direction, $includeNode);
    }

}