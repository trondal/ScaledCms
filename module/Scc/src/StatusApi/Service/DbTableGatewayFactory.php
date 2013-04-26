<?php

namespace StatusApi\Service;

use StatusApi\StatusDbTableGateway;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DbTableGatewayFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $services) {
        $adapter = $services->get('StatusApi\DbAdapter');
        $config = $services->get('Config');
        $table = 'status';
        if (isset($config['status_api']) && isset($config['status_api']['table'])) {
            $table = $config['status_api']['table'];
        }
        $em = $services->get('Doctrine\ORM\EntityManager');

        $statusDbTable = new StatusDbTableGateway($adapter, $table);
        $statusDbTable->setEntityManager($em);
        return $statusDbTable;
    }

}