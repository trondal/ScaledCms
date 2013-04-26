<?php

namespace StatusApi\Service;

use StatusApi\StatusDbPersistence;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StatusDbPersistenceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $services) {
        $em = $services->get('Doctrine\ORM\EntityManager');
        $table = $services->get('StatusApi\DbTableGateway');

        $persistence = new StatusDbPersistence($table);
        //$persistence = new StatusDbPersistence();
        $persistence->setEntityManager($em);
        return $persistence;
    }

}