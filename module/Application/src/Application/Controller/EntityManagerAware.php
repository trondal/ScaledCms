<?php

namespace Application\Controller;

use Doctrine\ORM\EntityManager;

interface EntityManagerAware {

    public function setEntityManager(EntityManager $em);
}