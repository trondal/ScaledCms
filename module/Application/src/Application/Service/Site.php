<?php

namespace Application\Service;

use Application\Controller\EntityManagerAware;
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
        return $this->em->find('Application\Entity\Site', $id);
    }

    public function findOneByName($name) {
        return $this->em->getRepository('Application\Entity\Site')
                        ->findOneBy(array('name' => $name));
    }

}