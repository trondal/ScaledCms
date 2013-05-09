<?php

namespace Scc;

use PhlyRestfully\ApiProblem;
use PhlyRestfully\View\RestfulJsonModel;
use Scc\Service\Factory\AclFactory;
use Scc\View\Helper\Contact;
use Scc\View\Helper\Twitter;
use Zend\Console\Adapter\AdapterInterface;
use Zend\EventManager\EventInterface;
use Zend\Http\Response;
use Zend\Http\Response as HttpResponse;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Paginator\Paginator;

class Module implements BootstrapListenerInterface, ConfigProviderInterface,
        AutoloaderProviderInterface, ServiceProviderInterface, ConsoleUsageProviderInterface,
        ViewHelperProviderInterface {

    public function onBootstrap(EventInterface $e) {
        $app = $e->getApplication();

        $eventManager = $app->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        // Add auth protection for admin route
        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this,  'authAdminControl'), -100);

        // Add auth protection for api route
        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'authApiControl'), -90);

        // Add metadata to collections
        $eventManager->attach(MvcEvent::EVENT_DISPATCH,
            array($this, 'onDispatchCollection'),
            -1
        );

        // For errors in the ResourceControllers and in. Not for module.php
        /*$sm = $app->getServiceManager();
          $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, function() use($sm, $eventManager) {
          //$application = $mvcEvent->getApplication();
          $apiProblemListener = $sm->getServiceManager()->get('PhlyRestfully\ApiProblemListener');
          $eventManager->attach($apiProblemListener);
        }, 1);*/

        // Add ACL for Api route
        //$eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'checkAccessControl'), -100);
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/../../autoload_classmap.php',
            ),
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
                },
                'doctrine.cache.scc_memcache' => function () {
                    $cache = new \Doctrine\Common\Cache\MemcacheCache();
                    $memcache = new \Memcache();
                    $memcache->connect('localhost', 11211);
                    $cache->setMemcache($memcache);
                    return $cache;
                },
            )
        );
    }

    public function authApiControl(MvcEvent $e) {
        $routeMatch = $e->getRouteMatch();

        if ($routeMatch->getMatchedRouteName() !== 'api/api-segment') {
            // No api route requested.
            return;
        }

        // Change this to the Rest login controller
        $controller = $routeMatch->getParam('controller');
        $request = $e->getRequest();
        if ($controller === 'Scc\Controller\Login' && $request->isPost()) {
            // user can freely access login/index
            return;
        }

        $sm = $e->getApplication()->getServiceManager();
        $authService = $sm->get('Zend\Authentication\AuthenticationService');

        if ($authService->hasIdentity() === true) {
            // Already authenticated
            return;
        }

        $sharedEventManager = $e->getApplication()->getEventManager()->getSharedManager();

        $sharedEventManager->attach('PhlyRestfully\ResourceController', 'dispatch', function ($e) {
                    $problem = new ApiProblem(HttpResponse::STATUS_CODE_401, 'Must be logged in to access the api.');
                    $e->setParam('api-problem', $problem);
                }, 100);

    }

    public function authAdminControl(MvcEvent $e) {

        $routeMatch = $e->getRouteMatch();

        if ($routeMatch->getMatchedRouteName() !== 'admin/admin-segment') {
            // No admin route requested.
            return;
        }

        $controller = $routeMatch->getParam('controller');
        $action = $routeMatch->getParam('action');

        if ($controller === 'Scc\Controller\Admin' && $action === 'login') {
            // User can access login/index freely
            return;
        }

        $sm = $e->getApplication()->getServiceManager();
        $authService = $sm->get('Zend\Authentication\AuthenticationService');

        if ($authService->hasIdentity() === true) {
            // Already authenticated
            return;
        }
        // not authenticated, redirect to login
        $url = $e->getRouter()->assemble(array(
            'domain' => $routeMatch->getParam('domain'),
            'tld' => $routeMatch->getParam('tld'),
            'controller' => 'admin',
            'action' => 'login'),
                array('name' => 'admin/admin-segment')
        );

        $response = $e->getResponse();
        $response->getHeaders()->addHeaderLine('Location', $url);
        $response->setStatusCode(302);
        $response->sendHeaders();
        return $response;

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
            'db create' => 'Create database from Doctrine Metadata',
            'db drop' => 'Drop database from Doctrine Metadata',
            'db rebuild' => 'Drop, create and populate database from Doctrine Metadata'
        );
    }

    public function getViewHelperConfig() {
        return array(
            'factories' => array(
                'Twitter' => function($sm) {
                    $helper = new Twitter();
                    $locator = $sm->getServiceLocator();
                    $helper->setEntityManager($locator->get('Doctrine\ORM\EntityManager'));
                    return $helper;
                },
                'Contact' => function($sm) {
                    $locator = $sm->getServiceLocator();
                    $request = $locator->get('Request');
                    $helper = new Contact();
                    $helper->setEntityManager($locator->get('Doctrine\ORM\EntityManager'));
                    $helper->setRequest($request);
                    return $helper;
                },
            )
        );
    }

    public function onDispatchCollection(MvcEvent $e) {
        $routeMatch = $e->getRouteMatch();

        if ($routeMatch->getMatchedRouteName() !== 'api/api-segment') {
            // No api route requested.
            return;
        }

        $result = $e->getResult();

        if (!$result instanceof RestfulJsonModel) {
            // Not jsonModel, would not decode anyway
            return;
        }
        if (!$result->isHalCollection()) {
            // Not a HalCollection, out of scope for this method.
            return;
        }
        $collection = $result->getPayload();

        if (!$collection->collection instanceof Paginator) {
            return;
        }

        $collection->setAttributes(array(
            'count'    => $collection->collection->getTotalItemCount(),
            'page'     => $collection->page,
            'per_page' => $collection->pageSize
        ));
    }
}