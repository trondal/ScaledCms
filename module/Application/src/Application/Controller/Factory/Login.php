<?php

namespace Application\Controller\Factory;

use Application\Controller\LoginController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Login implements FactoryInterface {

    public function createService(ServiceLocatorInterface $services) {
	$serviceLocator = $services->getServiceLocator();

	$controller = new LoginController;
	$controller->setAuthService($serviceLocator->get('Zend\Authentication\AuthenticationService'));
	return $controller;
    }

}