<?php

namespace SccTest\Entity;

class ContactTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function initialState() {
	$component = new \Scc\Entity\Contact('', '', '');

	$this->assertEquals('', $component->getTitle());
	$this->assertEquals('', $component->getRecipient());
	$this->assertEquals('', $component->getMsg());
    }

    /**
     * @test
     */
    public function settersAndGetters() {
	$component = new \Scc\Entity\Contact('', '', '');
	$component->setTitle('title');
	$component->setRecipient('recipient');
	$component->setMsg('message');

	$this->assertEquals('title', $component->getTitle());
	$this->assertEquals('recipient', $component->getRecipient());
	$this->assertEquals('message', $component->getMsg());
	$this->assertEquals('Scc\Entity\Contact', $component->getClassName());
    }
}