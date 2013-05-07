<?php

namespace SccTest\Entity;

class SiteTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function initialState() {
	$site = new \Scc\Entity\Site('', '');

	$this->assertEquals('', $site->getTitle());

	$this->assertCount(0, $site->getPages());
	$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $site->getPages());
    }

    /**
     * @test
     */
    public function settersAndGetters() {
	$site = new \Scc\Entity\Site('', '');
	$site->setTitle('Title');

	$pageMock = $this->getMockBuilder('Scc\Entity\Page')->disableOriginalConstructor(true)->getMock();
	$site->addPage($pageMock);

	$this->assertEquals('Title', $site->getTitle());
	$this->assertCount(1, $site->getPages());
    }

}