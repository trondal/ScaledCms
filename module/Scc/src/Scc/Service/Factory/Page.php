<?php

namespace Scc\Service\Factory;

class Page implements \Zend\ServiceManager\FactoryInterface {

    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
	$service = new \Scc\Service\PageService();
	$service->setEntityManager($serviceLocator->get('Doctrine\ORM\EntityManager'));

	return $service;
    }
}