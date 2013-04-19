<?php

namespace Scc\Service\Factory;

class TwitterServiceFactory implements \Zend\ServiceManager\FactoryInterface {
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $service = new \Scc\Service\TwitterService();
        $service->setEntityManager($serviceLocator->get('Doctrine\ORM\EntityManager'));
        return $service;
    }
    
}