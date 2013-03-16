<?php

namespace SccTest\Entity;

class NodeTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function initialState() {
	$componentMock = $this->getMockBuilder('Scc\Entity\Facebook')->disableOriginalConstructor()->getMock();
	$componentMock->expects($this->any())
		->method('getClassName')
		->will($this->returnValue('Facebook'));
	$node = new \Scc\Entity\Node($componentMock);

	$this->assertNull($node->getId());
	$this->assertNull($node->getPage());
	$this->assertEquals('Facebook', $node->getClassName());
	$this->assertInstanceOf('Scc\Entity\Facebook', $node->getComponent());
    }

    /**
     * @test
     */
    public function settersAndGetters() {
	$componentMock = $this->getMockBuilder('Scc\Entity\Facebook')->disableOriginalConstructor()->getMock();

	$node = new \Scc\Entity\Node($componentMock);

	$componentMock2 = $this->getMockBuilder('Scc\Entity\Twitter')->disableOriginalConstructor()->getMock();
	$componentMock2->expects($this->any())
		->method('getClassName')
		->will($this->returnValue('Twitter'));
	$pageMock = $this->getMockBuilder('Scc\Entity\Page')->disableOriginalConstructor(true)->getMock();


	$node->setComponent($componentMock2);
	$node->setPage($pageMock);

	$this->assertInstanceOf('Scc\Entity\Page', $node->getPage());
	$this->assertEquals('Twitter', $node->getClassName());
    }

}