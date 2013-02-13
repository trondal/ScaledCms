<?php

namespace Application\Service;

use Application\Controller\EntityManagerAware;
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
        return $this->em->find('Application\Entity\User', $id);
    }

    public function findOneByName($name) {
        return $this->em->getRepository('Application\Entity\User')
                ->findOneBy(array('name' => $name));
    }

}