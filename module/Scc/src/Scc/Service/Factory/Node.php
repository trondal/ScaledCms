<?php

namespace Scc\Service\Factory;

use Scc\Service\NodeService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Node implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
	$service = new NodeService();
	$service->setEntityManager($serviceLocator->get('Doctrine\ORM\EntityManager'));

	return $service;
    }

}