<?php

namespace Scc\Service;

use Scc\Controller\EntityManagerAware;
use Doctrine\ORM\EntityManager;

class SiteService implements EntityManagerAware {

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

    public function findOneByHostName($hostName) {
        $query = $this->em->createQuery('SELECT s FROM Scc\Entity\Site s JOIN s.hostNames h
            WHERE h.hostName = :hostName')
            ->setParameter('hostName', $hostName);
        return $query->getOneOrNullResult();
    }
}