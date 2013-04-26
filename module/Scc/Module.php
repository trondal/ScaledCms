<?php

namespace Scc;

use PhlyRestfully\HalResource;
use PhlyRestfully\Link;
use PhlyRestfully\LinkCollectionAwareInterface;
use PhlyRestfully\View\RestfulJsonModel;
use Scc\Service\Factory\AclFactory;
use Scc\View\Helper\Contact;
use Scc\View\Helper\Twitter;
use StatusApi\Status;
use StatusApi\StatusPersistenceInterface;
use Zend\Console\Adapter\AdapterInterface;
use Zend\Console\Response;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Paginator\Paginator;

class Module implements BootstrapListenerInterface, ConfigProviderInterface, AutoloaderProviderInterface, ServiceProviderInterface, ConsoleUsageProviderInterface, ViewHelperProviderInterface {

    public function onBootstrap(EventInterface $e) {
        $app = $e->getApplication();
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach('route', array($this, 'onRoute'), -100);

        $sharedEvents = $eventManager->getSharedManager();
        $sharedEvents->attach(
                'PhlySimplePage\PageController', 'dispatch', array($this, 'onDispatchDocs'), -1
        );
        /* $app->getEventManager()->attach(MvcEvent::EVENT_ROUTE,
          array($this, 'checkAuthenticationControl'), -90
          );

          $app->getEventManager()->attach(MvcEvent::EVENT_ROUTE,
          array($this, 'checkAccessControl'), -100
          ); */
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    'StatusApi' => __DIR__ . '/src/StatusApi',
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

    public function onRoute($e) {
        $controllers = 'StatusApi\StatusResourceController';

        $matches = $e->getRouteMatch();
        if (!$matches) {
            return;
        }
        $controller = $matches->getParam('controller', false);
        if (!in_array($controller, array('StatusApi\StatusResourcePublicController', 'StatusApi\StatusResourceUserController'))) {
            return;
        }

        $app = $e->getTarget();
        $services = $app->getServiceManager();
        $events = $app->getEventManager();
        $sharedEvents = $events->getSharedManager();
        $user = $matches->getParam('user', false);

        // Add a "Link" header pointing to the documentation
        $sharedEvents->attach(
                $controllers, 'dispatch', array($this, 'setDocumentationLink'), 10
        );

        // Add a "describedby" relation to resources
        $sharedEvents->attach(
                $controllers, array('getList.post', 'get.post', 'create.post', 'patch.post', 'update.post'), array($this, 'setDescribedByRelation')
        );

        // Add metadata to collections
        $sharedEvents->attach(
                $controllers, 'dispatch', array($this, 'onDispatchCollection'), -1
        );

        $sharedEvents->attach($controllers, 'getList.post', function ($e) {
                    $collection = $e->getParam('collection');
                    $collection->setResourceRoute('status_api/user');
                });

        // Set a listener on the renderCollection.resource event to ensure 
        // individual status links pass in the user to the route.
        $helpers = $services->get('ViewHelperManager');
        $links = $helpers->get('HalLinks');
        $links->getEventManager()->attach('renderCollection.resource', function ($e) use ($user) {
                    $eventParams = $e->getParams();
                    $route = $eventParams['route'];
                    $routeParams = $eventParams['routeParams'];

                    if ($route != 'status_api/user' && $route != 'status_api/public') {
                        return;
                    }

                    $resource = $eventParams['resource'];

                    if ($resource instanceof Status) {
                        $eventParams['route'] = 'status_api/user';
                        $eventParams['routeParams']['user'] = $resource->getUser();
                        return;
                    }

                    if (!is_array($resource)) {
                        return;
                    }

                    if (!isset($resource['user'])) {
                        return;
                    }

                    $eventParams['route'] = 'status_api/user';
                    $eventParams['routeParams']['user'] = $resource['user'];
                });

        if (!$user) {
            return;
        }

        // Set the user in the persistence listener
        $persistence = $services->get('StatusApi\PersistenceListener');
        if (!$persistence instanceof StatusPersistenceInterface) {
            return;
        }
        $persistence->setUser($user);
    }

    public function onDispatchDocs($e) {
        $route = $e->getRouteMatch()->getMatchedRouteName();
        $base = 'status_api/documentation';
        if (strlen($route) < strlen($base) || 0 !== strpos($route, $base)) {
            return;
        }

        $model = $e->getResult();
        $model->setTerminal(true);

        $response = $e->getResponse();
        $headers = $response->getHeaders();

        if ($route == $base) {
            $headers->addHeaderLine('content-type', 'text/x-markdown');
            return;
        }

        $headers->addHeaderLine('content-type', 'application/json');
    }

    public function setDocumentationLink($e) {
        $controller = $e->getTarget();
        $docsUrl = $controller->halLinks()->createLink('status_api/documentation', false);
        $response = $e->getResponse();
        $response->getHeaders()->addHeaderLine(
                'Link', sprintf('<%s>; rel="describedby"', $docsUrl)
        );
    }

    public function onDispatchCollection($e) {
        $result = $e->getResult();
        if (!$result instanceof RestfulJsonModel) {
            return;
        }
        if (!$result->isHalCollection()) {
            return;
        }
        $collection = $result->getPayload();

        if (!$collection->collection instanceof Paginator) {
            return;
        }
        $collection->setAttributes(array(
            'count' => $collection->collection->getTotalItemCount(),
            'page' => $collection->page,
            'per_page' => $collection->pageSize,
        ));
    }

    public function setDescribedByRelation($e) {
        $resource = $e->getParam('resource', false);
        if (!$resource) {
            $resource = $e->getParam('collection', false);
        }

        if (!$resource instanceof LinkCollectionAwareInterface) {
            return;
        }
        $link = new Link('describedby');

        if ($resource instanceof HalResource) {
            $link->setRoute('status_api/documentation/status');
        } else {
            $link->setRoute('status_api/documentation/collection');
        }
        $resource->getLinks()->add($link);
    }

}