<?php

namespace Scc\Service\Factory;

use ReflectionClass;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\GenericResource;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AclServiceFactory implements FactoryInterface {

    const GUEST = 'guest';
    const ADMIN  = 'admin';

    public function createService(ServiceLocatorInterface $serviceLocator) {
	$acl = new Acl();

        $guestRole = new GenericRole(self::GUEST);
        $adminRole = new GenericRole(self::ADMIN);

        $acl->addRole($guestRole);
        $acl->addRole($adminRole, $guestRole);

	$indexControllerRefClass = new ReflectionClass('Scc\Controller\IndexController');
	$indexControllerResource = new GenericResource($indexControllerRefClass->getMethod('getResourceId')->class);

	$acl->addResource($indexControllerResource);
        $acl->allow($guestRole, $indexControllerResource, array(
	    $indexControllerRefClass->getMethod('indexAction')->name
	));

	$adminControllerRefClass = new ReflectionClass('Scc\Controller\AdminController');
	$adminControllerResource = new GenericResource($adminControllerRefClass->getMethod('getResourceId')->class);

	$acl->addResource($adminControllerResource);
        $acl->allow($adminRole, $adminControllerResource, array(
	    $adminControllerRefClass->getMethod('indexAction')->name
	));

	$consoleControllerRefClass = new ReflectionClass('Scc\Controller\ConsoleController');
	$consoleControllerResource = new GenericResource($consoleControllerRefClass->getMethod('getResourceId')->class);

	$acl->addResource($consoleControllerResource);
        $acl->allow($guestRole, $consoleControllerResource, array(
	    $consoleControllerRefClass->getMethod('createAction')->name,
	    $consoleControllerRefClass->getMethod('dropAction')->name,
	    $consoleControllerRefClass->getMethod('rebuildAction')->name
	));

	$loginControllerRefClass = new ReflectionClass('Scc\Controller\LoginController');
	$loginControllerResource = new GenericResource($loginControllerRefClass->getMethod('getResourceId')->class);

	$acl->addResource($loginControllerResource);
        $acl->allow($guestRole, $loginControllerResource, array(
	    $loginControllerRefClass->getMethod('indexAction')->name
	));

	return $acl;
    }

}