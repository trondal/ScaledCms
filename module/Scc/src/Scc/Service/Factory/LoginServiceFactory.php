<?php

namespace Scc\Service\Factory;

use PhlyRestfully\Resource;
use Scc\Service\LoginService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoginServiceFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceManager) {
        $events = $serviceManager->get('EventManager');
        $resource = new Resource();
        $resource->setEventManager($events);
        
        $em = $serviceManager->get('Doctrine\ORM\EntityManager');
        $listener = new LoginService();

        $listener->setEntityManager($em);
        $listener->setAuthService($serviceManager->get('Zend\Authentication\AuthenticationService'));
        $listener->setAuthAttemptService($serviceManager->get('Scc\Service\AuthAttemptService'));
        $events->attach($listener);

        return $resource;
    }
    
}