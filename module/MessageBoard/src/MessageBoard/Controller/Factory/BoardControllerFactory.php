<?php

namespace MessageBoard\Controller\Factory;

use MessageBoard\Controller\BoardController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BoardControllerFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $service) {
	$serviceLocator = $service->getServiceLocator();

	$userService = $serviceLocator->get('Scc\Service\User');
	$boardService = $serviceLocator->get('MessageBoard\Service\BoardService');

	$controller = new BoardController();
	$controller->setUserService($userService);
	$controller->setBoardService($boardService);

	return $controller;
    }
}