<?php

namespace StatusApi\Service;

use StatusApi\StatusService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StatusServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $services) {
        $em = $services->get('Doctrine\ORM\EntityManager');
        //$table = $services->get('StatusApi\DbTableGateway');

        $service = new StatusService();
        //$persistence = new StatusDbPersistence();
        $service->setEntityManager($em);
        return $service;
    }

}