<?php

namespace ApplicationTest\Entity;

class UserTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function initialState() {
	$user = new \Application\Entity\User('', '', '');

	$this->assertNull($user->getId());
	$this->assertEquals('', $user->getName());
	$this->assertEquals('', $user->getPassword());
	$this->assertEquals('', $user->getEmail());

	$this->assertCount(0, $user->getSites());
	$this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $user->getSites());
    }

    /**
     * @test
     */
    public function settersAndGetters() {
	$user = new \Application\Entity\User('', '', '');
	$user->setName('Name');
	$user->setPassword('Password');
	$user->setEmail('email@snakeoil.com');

	$site = new \Application\Entity\Site('Name', 'Slug');
	$user->addSite($site);

	$this->assertEquals('Name', $user->getName());
	$this->assertEquals('Password', $user->getPassword());
	$this->assertEquals('email@snakeoil.com', $user->getEmail());
	$this->assertCount(1, $user->getSites());
    }

}