<?php

namespace Scc\Controller\Factory;

class Page implements \Zend\ServiceManager\FactoryInterface {

    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
	$locator = $serviceLocator->getServiceLocator();
	$controller = new \Scc\Controller\PageController();
	$controller->setUserService($locator->get('Scc\Service\User'));
	$controller->setSiteService($locator->get('Scc\Service\Site'));

	return $controller;
    }

}