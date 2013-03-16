<?php

namespace Scc\Service;

use Scc\Controller\EntityManagerAware;
use Doctrine\ORM\EntityManager;

class Site implements EntityManagerAware {

    /**
     * @var EntityManager
     */
    protected $em;

    public function setEntityManager(EntityManager $em) {
	$this->em = $em;
    }

    public function find($id) {
	return $this->em->find('Scc\Entity\Site', $id);
    }

    public function findOneByName($name) {
	return $this->em->getRepository('Scc\Entity\Site')
			->findOneBy(array('name' => $name));
    }

}