<?php

namespace Scc\Service;

use Scc\Controller\EntityManagerAware;
use Doctrine\ORM\EntityManager;

class User implements EntityManagerAware {

    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    public function setEntityManager(EntityManager $em) {
	$this->em = $em;
    }

    public function find($id) {
	return $this->em->find('Scc\Entity\User', $id);
    }

    public function findOneByName($name) {
	return $this->em->getRepository('Scc\Entity\User')
			->findOneBy(array('name' => $name));
    }

}