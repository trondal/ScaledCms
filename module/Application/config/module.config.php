<?php

namespace Application;

return array(
    'router' => array(
	'routes' => array(
	    'index' => array(
		'type' => 'Zend\Mvc\Router\Http\Hostname',
		'options' => array(
		    'route' => 'mobil.scaledcms.local',
		    'defaults' => array(
			'__NAMESPACE__' => 'Application\Controller',
			'controller' => 'Index',
			'action' => 'index'
		    )
		),
		'may_terminate' => true,
		'child_routes' => array(
		    'userslug' => array(
			'type' => 'Zend\Mvc\Router\Http\Segment',
			'options' => array(
			    'route' => '/[:a[/:b[/:c[/:d[/]]]]]',
			    'constraints' => array(
				'a' => '[a-zA-Z][a-zA-Z0-9_-]+',
				'b' => '[a-zA-Z][a-zA-Z0-9_-]+',
				'c' => '[a-zA-Z][a-zA-Z0-9_-]+',
				'd' => '[a-zA-Z][a-zA-Z0-9_-]+',
				'e' => '[a-zA-Z][a-zA-Z0-9_-]+',
			    ),
			    'defaults' => array(
				'controller' => 'Index',
				'action' => 'index',
			    ),
			),
		    )
		)
	    ),
	    'admin' => array(
		'type' => 'Zend\Mvc\Router\Http\Hostname',
		'options' => array(
		    'route' => 'admin.scaledcms.local',
		    'defaults' => array(
			'__NAMESPACE__' => 'Application\Controller',
		    )
		),
		'may_terminate' => false,
                'child_routes' => array(
                    'admin-segment' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
				'controller' => 'Admin',
				'action' => 'index'
                            )
                        )
                    )
                )
	    )
	)
    ),
    'console' => array(
	'router' => array(
	    'routes' => array(
		'restart' => array(
		    'options' => array(
			'route' => 'db restart',
			'defaults' => array(
			    'controller' => 'Application\Controller\Console',
			    'action' => 'restart'
			)
		    )
		)
	    )
	)
    ),
    'service_manager' => array(
	'invokables' => array(

	),
	'factories' => array(
	    'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
	    'Application\Service\User' => 'Application\Service\Factory\User',
	    'Application\Service\Site' => 'Application\Service\Factory\Site',
	    'Application\Service\Acl' =>  'Application\Service\Factory\AclFactory',
	    'Zend\Authentication\AuthenticationService' => function($serviceManager) {
		return $serviceManager->get('doctrine.authenticationservice.orm_default');
            },
            'Application\Service\PageService' => 'Application\Service\Factory\Page',
	    'Application\Form\LoginForm' => 'Application\Form\Factory\LoginFormFactory'
	)
    ),
    'translator' => array(
	'locale' => 'en_US',
	'translation_file_patterns' => array(
	    array(
		'type' => 'phparray',
		'base_dir' => __DIR__ . '/../language',
		'pattern' => '%s.php',
	    ),
	),
    ),
    'controllers' => array(
	'invokables' => array(
	    'Application\Controller\Facebook' => 'Application\Controller\FacebookController',
	    'Application\Controller\Twitter' => 'Application\Controller\TwitterController',
	    'Application\Controller\Login' => 'Application\Controller\LoginController',
	),
	'factories' => array(
	    'Application\Controller\Index' => 'Application\Controller\Factory\Index',
	    'Application\Controller\Console' => 'Application\Controller\Factory\Console',
	    'Application\Controller\Page' => 'Application\Controller\Factory\Page',
	    'Application\Controller\Admin' => 'Application\Controller\Factory\Admin'
	)
    ),
    'view_manager' => array(
	'display_not_found_reason' => false,
	'display_exceptions' => false,
	'doctype' => 'HTML5',
	'not_found_template' => 'error/404',
	'exception_template' => 'error/index',
	'template_map' => array(
	    'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
	    'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
	    'error/404' => __DIR__ . '/../view/error/404.phtml',
	    'error/index' => __DIR__ . '/../view/error/index.phtml',
	),
	'template_path_stack' => array(
	    __DIR__ . '/../view',
	),
    ),
    'view_helpers' => array(
	'invokables' => array(
	    'tree' => 'Application\View\Helper\Tree'
	)
    ),
    'doctrine' => array(
	'eventmanager' => array(
	    'orm_default' => array(
		'subscribers' => array('Gedmo\Tree\TreeListener', 'Application\Event\NodeListener')
	    )
	),
    )
);