<?php

namespace SccTest\Entity;

class SiteTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function initialState() {
	$site = new \Scc\Entity\Site('', '');

	$this->assertEquals('', $site->getName());
	$this->assertEquals('', $site->getSlug());

	$this->assertCount(0, $site->getPages());
	$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $site->getPages());
    }

    /**
     * @test
     */
    public function settersAndGetters() {
	$site = new \Scc\Entity\Site('', '');
	$site->setName('Name');
	$site->setSlug('Slug');

	$pageMock = $this->getMockBuilder('Scc\Entity\Page')->disableOriginalConstructor(true)->getMock();
	$site->addPage($pageMock);

	$this->assertEquals('Name', $site->getName());
	$this->assertEquals('Slug', $site->getSlug());
	$this->assertCount(1, $site->getPages());
    }

}