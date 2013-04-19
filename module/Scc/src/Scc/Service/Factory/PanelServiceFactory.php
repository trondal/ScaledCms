<?php

namespace Scc\Service\Factory;

class PanelServiceFactory implements \Zend\ServiceManager\FactoryInterface {
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $service = new \Scc\Service\PanelService();
        $service->setentityManager($serviceLocator->get('Doctrine\ORM\EntityManager'));
        return $service;
    }
    
}