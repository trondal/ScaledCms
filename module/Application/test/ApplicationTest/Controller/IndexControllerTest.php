<?php

namespace ApplicationTest\Controller;

use ApplicationTest\Bootstrap;
use PHPUnit_Framework_TestCase;
use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\Stdlib\Parameters;

class IndexControllerTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
	parent::setUp();
    }

    /**
     * @test
     */
    public function testIndexActionCanBeAccessed() {
	$cl = Bootstrap::getServiceManager()->get('ControllerLoader');
	$indexController = $cl->get('Application\Controller\Index');

	$event = new MvcEvent();
	$routeMatch = new RouteMatch(
		array(
		    'controller' => 'index',
		    'action' => 'index'
		)
	);
	$event->setRouteMatch($routeMatch);
	$request = new Request();
	$request->setQuery(new Parameters(array('a' => '')));
	$indexController->setEvent($event);

	$pageMock = $this->getMockBuilder('Application\Entity\Page')->disableOriginalConstructor()->getMock();
	$pageMock->expects($this->any())
		->method('getNodes')
		->will($this->returnValue(array()));
	$pageServiceMock = $this->getMock('Application\Service\PageService');
	$pageServiceMock->expects($this->any())
		->method('findByMaterializedPath')
		->will($this->returnValue($pageMock));
	$pageServiceMock->expects($this->any())
		->method('getMaterializedPath')
		->will($this->returnValue('/dummy-url'));

	$indexController->setPageService($pageServiceMock);

	$indexController->dispatch($request);

	$response = $indexController->getResponse();
	$this->assertEquals(200, $response->getStatusCode());

    }

}