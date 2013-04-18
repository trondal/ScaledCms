<?php

namespace MessageBoard\Service\Factory;

use MessageBoard\Service\BoardService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BoardServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
	$service = new BoardService();
	$service->setEntityManager($serviceLocator->get('\Doctrine\ORM\EntityManager'));
	return $service;
    }

}