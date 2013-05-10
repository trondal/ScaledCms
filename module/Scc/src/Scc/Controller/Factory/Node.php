<?php

namespace Scc\Controller\Factory;

use Scc\Controller\NodeController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Node implements FactoryInterface {

    public function createService(ServiceLocatorInterface $services) {
	$serviceLocator = $services->getServiceLocator();
	$controller = new NodeController;
	$controller->setNodeService($serviceLocator->get('Scc\Service\NodeService'));

        return $controller;
    }

}