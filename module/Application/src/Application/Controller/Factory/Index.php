<?php

namespace Application\Controller\Factory;

use Application\Controller\IndexController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Index implements FactoryInterface {

    public function createService(ServiceLocatorInterface $services) {
        $serviceLocator = $services->getServiceLocator();

        $controller = new IndexController;
        $controller->setEntityManager($serviceLocator->get('Doctrine\ORM\EntityManager'));
        return $controller;
    }
}