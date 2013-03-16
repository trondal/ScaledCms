<?php

namespace Scc\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Scc\Entity\Node;

class NodeRepository extends EntityRepository {

    public function getComponent(Node $node) {
	$class = $node->getClassName();
	$fqcn = 'Scc\Entity\\' . $class;
	return $this->_em
		->createQuery('SELECT c FROM ' . $fqcn . ' c WHERE c.node = :node')
		->setParameter('node', $node)
		->getOneOrNullResult();
    }

}