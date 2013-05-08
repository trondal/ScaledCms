<?php

namespace Scc\Service\Factory;

use Scc\Service\TwitterService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TwitterServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceManager) {
        $em = $serviceManager->get('Doctrine\ORM\EntityManager');
        $service = new TwitterService();
        $service->setEntityManager($em);

        return $service;
    }

}