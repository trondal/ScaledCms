<?php

namespace Application\Controller\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Admin implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
	$controller = new \Application\Controller\AdminController();

	return $controller;
    }
}