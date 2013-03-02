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
	$request->setQuery(new Parameters(array('id' => 1)));
	$indexController->setEvent($event);

	$pageMock = $this->getMockBuilder('Application\Entity\Page')->disableOriginalConstructor()->getMock();
	$pageMock->expects($this->any())
		->method('getNodes')
		->will($this->returnValue(array()));
	$emMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
	$emMock->expects($this->any())
		->method('find')
		->will($this->returnValue($pageMock));
	$indexController->setEntityManager($emMock);

	$indexController->dispatch($request);

	$response = $indexController->getResponse();
	$this->assertEquals(200, $response->getStatusCode());

    }

}