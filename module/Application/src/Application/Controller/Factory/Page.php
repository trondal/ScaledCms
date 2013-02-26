<?php

namespace Application\Controller\Factory;

class Page implements \Zend\ServiceManager\FactoryInterface {

    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
	$locator = $serviceLocator->getServiceLocator();
	$controller = new \Application\Controller\PageController();
	$controller->setUserService($locator->get('Application\Service\User'));
	$controller->setSiteService($locator->get('Application\Service\Site'));

	return $controller;
    }

}