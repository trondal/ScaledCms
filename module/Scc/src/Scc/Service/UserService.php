<?php

namespace Scc\Service;

use Doctrine\ORM\EntityManager;
use Scc\Controller\EntityManagerAware;

class UserService implements EntityManagerAware {

    /**
     *
     * @var EntityManager
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