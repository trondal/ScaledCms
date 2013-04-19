<?php

return array(
    'router' => array(
	'routes' => array(
	    'messageboard' => array(
		'type' => 'segment',
		'options' => array(
		    'route' => '/messageboard[/:controller][/:action][/:id]',
		    'constraints' => array(
			'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
			'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
			'id' => '[0-9]+',
		    ),
		    'defaults' => array(
			'__NAMESPACE__' => 'MessageBoard\Controller'
		    )
		)
	    )
	)
    ),
    'controllers' => array(
	'invokables' => array(
	    'MessageBoard\Controller\Message' => 'MessageBoard\Controller\MessageController',
	),
	'factories' => array(
	    'MessageBoard\Controller\Board' => 'MessageBoard\Controller\Factory\BoardControllerFactory',
	)
    ),
    'view_manager' => array(
	'template_path_stack' => array(
	    __DIR__ . '/../view'
	)
    ),
    'view_helpers' => array(
	'factories' => array(
	    // new, edit and view board
	    'MessageBoard\View\Helper\Board' => function($sm) {
		return new \MessageBoard\View\Helper\Board($sm->getServiceLocator());
	    },
	// new , edit and view message
	/* 'MessageBoard\View\Helper\Message' => function ($sm) {
	  return new \MessageBoard\View\Helper\Message($sm->getServiceLocator());
	  } */
	)
    ),
    'doctrine' => array(
	'eventmanager' => array(
	    'orm_default' => array(
		'subscribers' => array('Gedmo\Timestampable\TimestampableListener')
	    )
	)
    ),
    'service_manager' => array(
	'factories' => array(
	    'MessageBoard\Service\MessageService' => 'MessageBoard\Service\Factory\MessageServiceFactory',
	    'MessageBoard\Service\BoardService' => 'MessageBoard\Service\Factory\BoardServiceFactory'
	)
    ),
);