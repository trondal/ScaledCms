<?php

namespace Scc\Controller\Factory;

use PhlyRestfully\ResourceController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Login implements FactoryInterface {

    public function createService(ServiceLocatorInterface $controllerManager) {
        $services = $controllerManager->getServiceLocator();
        $resource = $services->get('Scc\Service\LoginResource');

        $configuration = $services->get('Configuration');
        $pageSize = isset($configuration['api']['page_size']) ? $configuration['api']['page_size'] : 10;
        $controller = new ResourceController('Scc\Controller\Login');
        $controller->setResource($resource);
        $controller->setPageSize($pageSize);
        $controller->setRoute('api/api-segment');
        $controller->setCollectionName('login');

        $controller->setCollectionHttpOptions(array('GET', 'POST'));
        $controller->setResourceHttpOptions(array('GET', 'DELETE'));
        return $controller;
    }

}