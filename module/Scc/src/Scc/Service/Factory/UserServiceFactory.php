<?php

namespace Scc\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
	$service = new \Scc\Service\UserService();
	$service->setEntityManager($serviceLocator->get('Doctrine\ORM\EntityManager'));

	return $service;
    }

}