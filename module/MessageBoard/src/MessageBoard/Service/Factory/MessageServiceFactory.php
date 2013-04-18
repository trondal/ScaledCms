<?php

namespace MessageBoard\Service\Factory;

class MessageServiceFactory implements \Zend\ServiceManager\FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
	$service = new \MessageBoard\Service\MessageService();
	$service->setEntityManager($serviceLocator->get('\Doctrine\ORM\EntityManager'));
	return $service;
    }

}