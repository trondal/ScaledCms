<?php

namespace StatusApi\Service;

use StatusApi\StatusDbTable;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DbTableFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        $adapter = $services->get('StatusApi\DbAdapter');
        $config  = $services->get('Config');
        $table   = 'status';
        if (isset($config['status_api'])
            && isset($config['status_api']['table'])
        ) {
            $table = $config['status_api']['table'];
        }

        return new StatusDbTable($adapter, $table);
    }
}