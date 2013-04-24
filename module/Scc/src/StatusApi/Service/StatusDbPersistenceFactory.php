<?php

namespace StatusApi\Service;

use StatusApi\StatusDbPersistence;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StatusDbPersistenceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        $table = $services->get('StatusApi\DbTable');
        return new StatusDbPersistence($table);
    }
}