<?php

namespace ApplicationTest\Entity;

class TwitterTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function initialState() {
	$component = new \Application\Entity\Twitter('');

	$this->assertEquals('', $component->getHtml());
    }

    /**
     * @test
     */
    public function settersAndGetters() {
	$component = new \Application\Entity\Twitter('');
	$component->setHtml('Html');

	$this->assertEquals('Html', $component->getHtml());
	$this->assertEquals('Twitter', $component->getClassName());
    }
}