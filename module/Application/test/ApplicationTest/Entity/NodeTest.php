<?php

namespace ApplicationTest\Entity;

class NodeTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function initialState() {
	$componentMock = $this->getMockBuilder('Application\Entity\Facebook')->disableOriginalConstructor()->getMock();
	$componentMock->expects($this->any())
		->method('getClassName')
		->will($this->returnValue('Facebook'));
	$node = new \Application\Entity\Node($componentMock);

	$this->assertNull($node->getId());
	$this->assertNull($node->getPage());
	$this->assertEquals('Facebook', $node->getClassName());
	$this->assertInstanceOf('Application\Entity\Facebook', $node->getComponent());
    }

    /**
     * @test
     */
    public function settersAndGetters() {
	$componentMock = $this->getMockBuilder('Application\Entity\Facebook')->disableOriginalConstructor()->getMock();

	$node = new \Application\Entity\Node($componentMock);

	$componentMock2 = $this->getMockBuilder('Application\Entity\Twitter')->disableOriginalConstructor()->getMock();
	$componentMock2->expects($this->any())
		->method('getClassName')
		->will($this->returnValue('Twitter'));
	$pageMock = $this->getMockBuilder('Application\Entity\Page')->disableOriginalConstructor(true)->getMock();


	$node->setComponent($componentMock2);
	$node->setPage($pageMock);

	$this->assertInstanceOf('Application\Entity\Page', $node->getPage());
	$this->assertEquals('Twitter', $node->getClassName());
    }

}