<?php

namespace Scc\Service\Factory;

use PhlyRestfully\Resource;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TwitterResourceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceManager) {
        $events = $serviceManager->get('EventManager');
        $resource = new Resource();
        $resource->setEventManager($events);

        /*$em = $serviceManager->get('Doctrine\ORM\EntityManager');
        $listener = new \Scc\Service\TwitterService();

        $listener->setEntityManager($em);*/

        $listener = $serviceManager->get('Scc\Service\TwitterService');
        $events->attach($listener);

        return $resource;
    }

}