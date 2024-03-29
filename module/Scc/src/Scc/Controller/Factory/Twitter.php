<?php

namespace Scc\Controller\Factory;

use PhlyRestfully\ResourceController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Twitter implements FactoryInterface {

    public function createService(ServiceLocatorInterface $controllerManager) {
        $services   = $controllerManager->getServiceLocator();
        $resource   = $services->get('Scc\Service\TwitterResource');

        $configuration     = $services->get('Configuration');
        $pageSize   = isset($configuration['app']['page_size']) ? $configuration['app']['page_size'] : 10;
        $controller = new ResourceController('Scc\Controller\Twitter');
        $controller->setResource($resource);
        $controller->setPageSize($pageSize);
        $controller->setRoute('api/api-segment');
        $controller->setCollectionName('twitter');
        return $controller;
    }

}