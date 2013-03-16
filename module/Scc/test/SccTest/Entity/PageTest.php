<?php

namespace SccTest\Entity;

class PageTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function initialState() {
	$page = new \Scc\Entity\Page('', '');

	$this->assertNull($page->getId());
	$this->assertEquals('', $page->getTitle());
	$this->assertEquals('', $page->getSlug());
	$this->assertNull($page->getLeft());
	$this->assertNull($page->getRight());
	$this->assertNull($page->getSite());

	$this->assertCount(0, $page->getChildren());
	$this->assertCount(0, $page->getNodes());

	$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $page->getChildren());
	$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $page->getNodes());
    }

    /**
     * @test
     */
    public function settersAndGetters() {
	$page = new \Scc\Entity\Page('', '');
	$page->setTitle('Title');
	$page->setSlug('Slug');

	$nodeMock = $this->getMockBuilder('Scc\Entity\Node')->disableOriginalConstructor(true)->getMock();
	$page->addNode($nodeMock);

	$this->assertEquals('Title', $page->getTitle());
	$this->assertEquals('Slug', $page->getSlug());
	$this->assertCount(1, $page->getNodes());
    }

}