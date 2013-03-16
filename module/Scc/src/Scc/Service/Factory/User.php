<?php

namespace Scc\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class User implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
	$service = new \Scc\Service\User();
	$service->setEntityManager($serviceLocator->get('Doctrine\ORM\EntityManager'));

	return $service;
    }

}