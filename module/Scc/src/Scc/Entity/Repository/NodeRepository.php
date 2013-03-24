<?php

namespace Scc\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Scc\Entity\Node;

class NodeRepository extends EntityRepository {

    public function getComponent(Node $node) {
	$fqcName = $node->getClassName();
	return $this->_em
		->createQuery('SELECT c FROM ' . $fqcName . ' c WHERE c.node = :node')
		->setParameter('node', $node)
		->getOneOrNullResult();
    }

}