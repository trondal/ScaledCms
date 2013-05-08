<?php

namespace Application;

return array(
     'swagger' => array(
        /**
         * List a path or paths containing Swagger Annotated classes
         */
        'paths' => array(
        realpath(__DIR__ . '/../../module/Scc/src/Scc/Model'),
        ),
    ),
    'doctrine' => array(
	'connection' => array(
	    // default connection name
	    'orm_default' => array(
		'configuration' => 'orm_default',
		'eventmanager' => 'orm_default'
	    )
	),
	'configuration' => array(
	    'orm_default' => array(
		'metadata_cache' => 'scc_memcache',
		'query_cache' => 'scc_memcache',
		'result_cache' => 'scc_memcache',
		'driver' => 'orm_default',
		'generate_proxies' => true,
		'proxy_dir' => sys_get_temp_dir(),
		'proxy_namespace' => 'DoctrineORMModule\Proxy',
		'filters' => array()
	    )
	),
	'driver' => array(
	    'orm_default' => array(
		'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
		'cache' => 'scc_memcache'
	    )
	),
	'entitymanager' => array(
	    'orm_default' => array(
		'connection' => 'orm_default',
		'configuration' => 'orm_default'
	    )
	),
	'eventmanager' => array(
	    'orm_default' => array()
	),
	'entity_resolver' => array(
	    'orm_default' => array()
	)
    ),
    /*'swagger' => array(
        //List a path or paths containing Swagger Annotated classes
        'paths' => array(
            __DIR__ . '/../../module/Scc/src/Scc/Entity',
        ),
    )*/
);