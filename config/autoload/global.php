<?php

namespace Application;

return array(
    'db' => array(
        'driver'         => 'Pdo_Pgsql',
        'dsn'            => 'pgsql:dbname=scaledcms;host=localhost',
        'username' => 'scaledcms',
        'password' => 'scaledcms'
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
		'metadata_cache' => 'array',
		'query_cache' => 'array',
		'result_cache' => 'array',
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
		'cache' => 'array'
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
    )
);