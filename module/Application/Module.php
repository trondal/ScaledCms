<?php

namespace Application;

use Application\Service\Factory\AclFactory;
use Zend\Mvc\MvcEvent;

class Module {

    public function onBootstrap(MvcEvent $e) {
	$app = $e->getApplication();
	$app->getEventManager()->attach(MvcEvent::EVENT_ROUTE,
	    array($this, 'getGateKeeper'), -100
	);
    }

    public function getConfig() {
	return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
	return array(
	    'Zend\Loader\StandardAutoloader' => array(
		'namespaces' => array(
		    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
		),
	    ),
	);
    }

    /**
     * @param MvcEvent $e
     */
    public function getGateKeeper(MvcEvent $e) {
	$app = $e->getApplication();
	$sm = $app->getServiceManager();
	$routeMatch = $e->getRouteMatch();
	$acl = $sm->get('Application\Service\Acl');

	$authService = $sm->get('Zend\Authentication\AuthenticationService');

	$role = $authService->getIdentity() ? $authService->getIdentity()->getRole() : AclFactory::GUEST;
	$controller = $routeMatch->getParam('controller') . 'Controller';

	$action = $routeMatch->getParam('action') . 'Action';

	// TODO: does not handle console requests.
	if ($acl->isAllowed($role, $controller, $action) === false) {
	    $response = $e->getResponse();
	    $response->setStatusCode(402);
	    return $response;
	}
    }

}