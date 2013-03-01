<?php

namespace ApplicationTest\Controller;

use ApplicationTest\ControllerTestCase;

class IndexControllerTest extends ControllerTestCase {

    public function setUp() {
	parent::setUp();
    }

    public function testIndexActionCanBeAccessed() {
	$this->dispatch('http://mobil.scaledcms.local/?id=1');
	$this->assertResponseStatusCode(200);
    }

}