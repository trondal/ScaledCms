<?php

namespace ApplicationTest\Entity;

class FacebookTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function initialState() {
	$component = new \Application\Entity\Facebook('', '');

	$this->assertEquals('', $component->getHtml());
	$this->assertEquals('', $component->getToken());
    }

    /**
     * @test
     */
    public function settersAndGetters() {
	$component = new \Application\Entity\Facebook('', '');
	$component->setHtml('Html');
	$component->setToken('Token');

	$this->assertEquals('Html', $component->getHtml());
	$this->assertEquals('Token', $component->getToken());
	$this->assertEquals('Facebook', $component->getClassName());
    }
}