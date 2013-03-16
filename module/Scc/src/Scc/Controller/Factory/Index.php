<?php

namespace Scc\Controller\Factory;

use Scc\Controller\IndexController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Index implements FactoryInterface {

    public function createService(ServiceLocatorInterface $services) {
	$serviceLocator = $services->getServiceLocator();

	$controller = new IndexController;
	$controller->setPageService($serviceLocator->get('Scc\Service\PageService'));
	return $controller;
    }

}