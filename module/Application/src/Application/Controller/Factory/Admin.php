<?php

namespace Application\Controller\Factory;

use Application\Controller\AdminController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Admin implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
	$controller = new AdminController();

	return $controller;
    }
}