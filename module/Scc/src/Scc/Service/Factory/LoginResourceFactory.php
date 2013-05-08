<?php

namespace Scc\Service\Factory;

use PhlyRestfully\Resource;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoginResourceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceManager) {
        $events = $serviceManager->get('EventManager');
        $resource = new Resource();
        $resource->setEventManager($events);

        $listener = $serviceManager->get('Scc\Service\LoginService');
        $events->attach($listener);

        return $resource;
    }

}