<?php

namespace Scc\Controller\Factory;

use Scc\Controller\AdminController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Admin implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
	$controller = new AdminController();

	return $controller;
    }
}