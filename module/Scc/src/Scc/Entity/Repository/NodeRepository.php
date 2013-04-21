<?php

namespace Scc\Entity\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Scc\Entity\Node;

class NodeRepository extends NestedTreeRepository {

    public function getComponent(Node $node) {
	$fqcName = $node->getClassName();
	return $this->_em
		->createQuery('SELECT c FROM ' . $fqcName . ' c WHERE c.node = :node')
		->setParameter('node', $node)
		->getOneOrNullResult();
    }

}