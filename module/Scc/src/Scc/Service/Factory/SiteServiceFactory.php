<?php

namespace Scc\Service\Factory;

use Scc\Service\SiteService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SiteServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
	$service = new SiteService();
	$service->setEntityManager($serviceLocator->get('Doctrine\ORM\EntityManager'));

	return $service;
    }

}