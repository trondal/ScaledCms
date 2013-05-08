<?php

namespace Scc\Service\Factory;

use Scc\Service\PageService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PageServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
	$service = new PageService();
	$service->setEntityManager($serviceLocator->get('Doctrine\ORM\EntityManager'));

	return $service;
    }
}