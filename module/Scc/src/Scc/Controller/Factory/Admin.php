<?php

namespace Scc\Controller\Factory;

use Scc\Controller\AdminController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Admin implements FactoryInterface {

    public function createService(ServiceLocatorInterface $controllerManager) {
        $serviceLocator = $controllerManager->getServiceLocator();
	
        $controller = new AdminController();
        $controller->setAuthService($serviceLocator->get('Zend\Authentication\AuthenticationService'));
	return $controller;
    }
}