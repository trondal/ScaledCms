<?php

namespace Scc;

use Scc\Service\Factory\AclFactory;
use Zend\Console\Adapter\AdapterInterface;
use Zend\Console\Response;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module implements BootstrapListenerInterface, ConfigProviderInterface,
	AutoloaderProviderInterface, ServiceProviderInterface, ConsoleUsageProviderInterface, \Zend\ModuleManager\Feature\ViewHelperProviderInterface{

    public function onBootstrap(EventInterface $e) {
	$app = $e->getApplication();

	$eventManager = $e->getApplication()->getEventManager();
	$moduleRouteListener = new ModuleRouteListener();
	$moduleRouteListener->attach($eventManager);

	$app->getEventManager()->attach(MvcEvent::EVENT_ROUTE,
		array($this, 'checkAuthenticationControl'), -90
	);

	$app->getEventManager()->attach(MvcEvent::EVENT_ROUTE,
		array($this, 'checkAccessControl'), -100
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

    public function getServiceConfig() {
	return array(
	    'factories' => array(
		'Zend\Authentication\AuthenticationService' => function($serviceManager) {
		    return $serviceManager->get('doctrine.authenticationservice.orm_default');
		}
	    )
	);
    }

    public function checkAuthenticationControl(MvcEvent $e) {
	$routeMatch = $e->getRouteMatch();
	// No need to check auth for non-admin routes
	if ($routeMatch->getMatchedRouteName() != 'admin/admin-segment') {
	    return;
	}
	$sm = $e->getApplication()->getServiceManager();
	$authService = $sm->get('Zend\Authentication\AuthenticationService');
	if ($authService->hasIdentity() === false) {
	    $controller = $routeMatch->getParam('controller');
	    $action = $routeMatch->getParam('action');

	    if ($controller === 'Scc\Controller\Login' && $action === 'index') {
		// user can access login/index
		return;
	    }

	    $url = $e->getRouter()->assemble(
		    array('controller' => 'login', 'action' => 'index'), array('name' => 'admin/admin-segment')
	    );
	    $response = $e->getResponse();
	    $response->getHeaders()->addHeaderLine('Location', $url);
	    $response->setStatusCode(302);
	    $response->sendHeaders();
	    // When an MvcEvent Listener returns a Response object,
	    // It automatically short-circuits the Application running
	    return $response;
	}
    }

    /**
     * @param MvcEvent $e
     */
    public function checkAccessControl(MvcEvent $e) {
	$app = $e->getApplication();
	$sm = $app->getServiceManager();
	$routeMatch = $e->getRouteMatch();

	$acl = $sm->get('Scc\Service\Acl');

	$authService = $sm->get('Zend\Authentication\AuthenticationService');
	$role = $authService->getIdentity() ? AclFactory::ADMIN : AclFactory::GUEST;
	$controller = $routeMatch->getParam('controller') . 'Controller';

	$action = $routeMatch->getParam('action') . 'Action';

	if ($acl->isAllowed($role, $controller, $action) === false) {
	    $response = $e->getResponse();
	    if ($response instanceof Response) {
		echo 'Not allowed!';
		exit;
	    }

	    $response->setStatusCode(403);
	    return $response;
	}
    }

    public function getConsoleUsage(AdapterInterface $console) {
	return array(
            'db create'     => 'Create database from Doctrine Metadata',
	    'db drop' => 'Drop database from Doctrine Metadata',
	    'db rebuild' => 'Drop, create and populate database from Doctrine Metadata'
        );
    }
    
    public function getViewHelperConfig() {
        return array(
            'factories' => array(
                'Twitter' => function($sm) {
                    $helper = new \Scc\View\Helper\Twitter();
                    $locator = $sm->getServiceLocator();
                    $helper->setEntityManager($locator->get('Doctrine\ORM\EntityManager'));
                    return $helper;
                },
                'Contact' => function($sm) {
                    $locator = $sm->getServiceLocator();
                    $request = $locator->get('Request');
                    $helper = new \Scc\View\Helper\Contact();
                    $helper->setEntityManager($locator->get('Doctrine\ORM\EntityManager'));
                    $helper->setRequest($request);
                    return $helper;
                },
            )
        );
    }

}