<?php

namespace MessageBoard\Service;

use Doctrine\ORM\EntityManager;
use MessageBoard\Entity\Board;
use Scc\Controller\EntityManagerAware;

class BoardService implements EntityManagerAware {

    /**
     * @var EntityManager
     */
    protected $em;

    public function setEntityManager(EntityManager $em) {
	$this->em = $em;
    }

    public function save(Board $board) {
	$this->em->persist($board);
	$this->em->flush();
    }

    public function findByUser(\Scc\Entity\User $user) {
	$repo = $this->em->getRepository('MessageBoard\Entity\Board');
	return $repo->findByUser($user);
    }

    public function find($id) {
	$repo = $this->em->getRepository('MessageBoard\Entity\Board');
	return $repo->find($id);
    }

}