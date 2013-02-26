<?php

namespace Application\Controller\Factory;

use Application\Controller\ConsoleController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Console implements FactoryInterface {

    public function createService(ServiceLocatorInterface $services) {
	$serviceLocator = $services->getServiceLocator();

	$controller = new ConsoleController;
	$controller->setEntityManager($serviceLocator->get('Doctrine\ORM\EntityManager'));
	return $controller;
    }

}