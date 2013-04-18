<?php

namespace Scc;

use Scc\Entity\User;
use Zend\Crypt\Password\Bcrypt;

return array(
    'router' => array(
	'routes' => array(
	    'index' => array(
		'type' => 'Zend\Mvc\Router\Http\Hostname',
		'options' => array(
                    'priority' => 50,
		    'route' => 'mobil.:domain.:tld',
		    'defaults' => array(
			'__NAMESPACE__' => 'Scc\Controller',
			'controller' => 'Index',
			'action' => 'index'
		    )
		),
		'may_terminate' => true,
		'child_routes' => array(
		    'userslug' => array(
			'type' => 'Zend\Mvc\Router\Http\Segment',
			'options' => array(
			    'route' => '/[:a][/:b][/:c][/:d]',
			    'constraints' => array(
				'a' => '[a-zA-Z][a-zA-Z0-9_-]+',
				'b' => '[a-zA-Z][a-zA-Z0-9_-]+',
				'c' => '[a-zA-Z][a-zA-Z0-9_-]+',
				'd' => '[a-zA-Z][a-zA-Z0-9_-]+',
			    ),
			    'defaults' => array(
				'controller' => 'Index',
				'action' => 'index',
			    ),
			)
		    )
		)
	    ),
	    'admin' => array(
		'type' => 'Zend\Mvc\Router\Http\Hostname',
		'options' => array(
                    'priority' => 100,
		    'route' => 'admin.:domain.:tld',
		    'defaults' => array(
			'__NAMESPACE__' => 'Scc\Controller',
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
			    'controller' => 'Scc\Controller\Console',
			    'action' => 'restart'
			)
		    )
		)
	    )
	)
    ),
    'service_manager' => array(
	'factories' => array(
	    'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
	    'Scc\Service\User' => 'Scc\Service\Factory\User',
	    'Scc\Service\Site' => 'Scc\Service\Factory\Site',
	    'Scc\Service\Acl' =>  'Scc\Service\Factory\AclFactory',
            'Scc\Service\PageService' => 'Scc\Service\Factory\Page',
	    'Scc\Form\LoginForm' => 'Scc\Form\Factory\LoginFormFactory'
	)
    ),
    'translator' => array(
	'locale' => 'en_US',
	'translation_file_patterns' => array(
	    array(
		'type' => 'phparray',
		'base_dir' => __DIR__ . '/../language',
		'pattern' => '%s.php'
	    )
	)
    ),
    'controllers' => array(
	'invokables' => array(
	    'Scc\Controller\Facebook' => 'Scc\Controller\FacebookController',
	    'Scc\Controller\Twitter' => 'Scc\Controller\TwitterController'
	),
	'factories' => array(
	    'Scc\Controller\Index' => 'Scc\Controller\Factory\Index',
	    'Scc\Controller\Console' => 'Scc\Controller\Factory\Console',
	    'Scc\Controller\Page' => 'Scc\Controller\Factory\Page',
	    'Scc\Controller\Admin' => 'Scc\Controller\Factory\Admin',
	    'Scc\Controller\Login' => 'Scc\Controller\Factory\Login'
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
	    'error/index' => __DIR__ . '/../view/error/index.phtml'
	),
	'template_path_stack' => array(
	    __DIR__ . '/../view'
	)
    ),
    'view_helpers' => array(
	'invokables' => array(
	    'tree' => 'Scc\View\Helper\Tree'
	)
    ),
    'doctrine' => array(
	'eventmanager' => array(
	    'orm_default' => array(
		'subscribers' => array('Gedmo\Tree\TreeListener', 'Scc\Event\NodeListener')
	    )
	),
        'driver' => array(
            'orm_default' => array(
                'paths' => array(
		    __DIR__ . '/../src/Scc/Entity'
		)
            )
        ),
	'authentication' => array(
            'orm_default' => array(
                'objectManager' => 'Doctrine\ORM\EntityManager',
                'identityClass' => 'Scc\Entity\User',
                'identityProperty' => 'username',
                'credentialProperty' => 'password',
                'credentialCallable' => function(User $user, $passwordGiven) {
                    $bcrypt = new Bcrypt();
                    return $bcrypt->verify($passwordGiven, $user->getPassword());
                }
            )
        )
    )
);