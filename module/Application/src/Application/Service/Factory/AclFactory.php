<?php

namespace Application\Service\Factory;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\GenericResource;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AclFactory implements FactoryInterface {

    const GUEST = 'guest';
    const ADMIN  = 'admin';

    public function createService(ServiceLocatorInterface $serviceLocator) {
	$acl = new Acl();

        $guestRole = new GenericRole(self::GUEST);
        $adminRole = new GenericRole(self::ADMIN);

        $acl->addRole($guestRole);
        $acl->addRole($adminRole, $guestRole);

	$indexControllerRefClass = new \ReflectionClass('Application\Controller\IndexController');
	$indexControllerResource = new GenericResource($indexControllerRefClass->getMethod('getResourceId')->class);

	$acl->addResource($indexControllerResource);
        $acl->allow($guestRole, $indexControllerResource, array(
	    $indexControllerRefClass->getMethod('indexAction')->name
	));

	return $acl;
    }

}