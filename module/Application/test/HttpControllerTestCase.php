<?php

namespace ApplicationTest;

use Doctrine\ORM\Tools\SchemaTool;
use DoctrineORMModule\Options\EntityManager;
use Zend\ServiceManager\ServiceManager;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

abstract class HttpControllerTestCase extends AbstractHttpControllerTestCase {
    /**
     * @var ServiceManager
     */
    protected $sm;

    /**
     * @var EntityManager
     */
    protected $em;

    protected $traceError = true;

    public function setUp() {
	$this->setApplicationConfig(
		include 'TestConfig.php'
	);
	$this->sm = $this->getApplication()->getServiceManager();
	$this->em = $this->sm->get('Doctrine\ORM\EntityManager');
	$tool = new SchemaTool($this->em);
        $tool->createSchema($this->em->getMetadataFactory()->getAllMetadata());
	parent::setUp();
    }

}