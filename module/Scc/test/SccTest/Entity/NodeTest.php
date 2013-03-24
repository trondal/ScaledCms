<?php

namespace SccTest\Entity;

class NodeTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function initialState() {
	$componentMock = $this->getMockBuilder('Scc\Entity\Contact')->disableOriginalConstructor()->getMock();
	$componentMock->expects($this->any())
		->method('getClassName')
		->will($this->returnValue('Scc\Entity\Contact'));
	$node = new \Scc\Entity\Node($componentMock);

	$this->assertNull($node->getId());
	$this->assertNull($node->getPage());
	$this->assertEquals('Scc\Entity\Contact', $node->getClassName());
    }

    /**
     * @test
     */
    public function settersAndGetters() {
	$componentMock = $this->getMockBuilder('Scc\Entity\Contact')->disableOriginalConstructor()->getMock();
	$componentMock->expects($this->any())
		->method('getClassName')
		->will($this->returnValue('Scc\Entity\Twitter'));

	$node = new \Scc\Entity\Node($componentMock);

	$pageMock = $this->getMockBuilder('Scc\Entity\Page')->disableOriginalConstructor(true)->getMock();
	$node->setPage($pageMock);

	$this->assertInstanceOf('Scc\Entity\Page', $node->getPage());
	$this->assertEquals('Scc\Entity\Twitter', $node->getClassName());
    }

}