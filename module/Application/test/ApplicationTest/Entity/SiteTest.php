<?php

namespace ApplicationTest\Entity;

class SiteTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function initialState() {
	$site = new \Application\Entity\Site('', '');

	$this->assertEquals('', $site->getName());
	$this->assertEquals('', $site->getSlug());

	$this->assertCount(0, $site->getPages());
	$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $site->getPages());
    }

    /**
     * @test
     */
    public function settersAndGetters() {
	$site = new \Application\Entity\Site('', '');
	$site->setName('Name');
	$site->setSlug('Slug');

	$pageMock = $this->getMockBuilder('Application\Entity\Page')->disableOriginalConstructor(true)->getMock();
	$site->addPage($pageMock);

	$this->assertEquals('Name', $site->getName());
	$this->assertEquals('Slug', $site->getSlug());
	$this->assertCount(1, $site->getPages());
    }

}