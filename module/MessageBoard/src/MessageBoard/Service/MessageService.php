<?php

namespace MessageBoard\Service;

use Doctrine\ORM\EntityManager;
use Scc\Controller\EntityManagerAware;

class MessageService implements EntityManagerAware {

    /**
     * @var EntityManager
     */
    protected $em;

    public function setEntityManager(EntityManager $em) {
	$this->em = $em;
    }

}