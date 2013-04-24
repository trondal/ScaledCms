<?php

namespace StatusApi\Service;

use PhlyRestfully\ResourceController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StatusResourceUserControllerFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $controllers) {
        $services = $controllers->getServiceLocator();
        $resource = $services->get('StatusApi\StatusResource');
        $configuration = $services->get('config');
        $config = isset($configuration['status_api']) ? $configuration['status_api'] : array();
        $pageSize = isset($config['page_size']) ? $config['page_size'] : 10;

        $controller = new ResourceController('StatusApi\StatusResourceController');
        $controller->setResource($resource);
        $controller->setPageSize($pageSize);
        $controller->setRoute('status_api/user');
        $controller->setCollectionName('status');
        return $controller;
    }

}