<?php

namespace Scc\Controller;

use Scc\Entity\Facebook;
use Scc\Entity\Node;
use Scc\Entity\Page;
use Scc\Entity\Site;
use Scc\Entity\Twitter;
use Scc\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class ConsoleController extends AbstractActionController implements EntityManagerAware, ResourceInterface {

    /**
     * @var EntityManager
     */
    protected $em;

    public function setEntityManager(EntityManager $em) {
	$this->em = $em;
    }

    public function getResourceId() {
	return __CLASS__;
    }

    public function dropcreateAction() {
	$tool = new SchemaTool($this->em);
	$metaData = $this->em->getMetadataFactory()->getAllMetadata();

	$tool->dropSchema($metaData);
	$tool->createSchema($metaData);

	return 'Db dropped and created.' . PHP_EOL;
    }

    public function restartAction() {
	$this->dropcreateAction();

	// Add user and site
	$bcrypt = new \Zend\Crypt\Password\Bcrypt();
	$user1 = new User('Alice', $bcrypt->create('password'), 'alice@gmail.com');
	$user2 = new User('Bob', $bcrypt->create('password'), 'bob@gmail.com');

	$site1 = new Site('Alice\'s first site', 'alice');
	$site2 = new Site('Alice\'s second site', 'alice2');
	$site3 = new Site('Bob\'s site', 'bob');

	$user1->addSite($site1);
	$user1->addSite($site2);
	$user2->addSite($site3);

	$this->em->persist($user1);
	$this->em->persist($user2);
	$this->em->persist($site1);
	$this->em->persist($site2);
	$this->em->persist($site3);

	// Add pages
	$page = new Page('index');
	$page1_1 = new Page('Page 1-1', 'page1_1');
	$page1_2 = new Page('Page 1-2', 'page1_2');
	$page2_1 = new Page('Page 2-1', 'page2_1');
	$page2_2 = new Page('Page 2-2', 'page2_2');

	$page1_1->setParent($page);
	$page1_2->setParent($page);
	$page2_1->setParent($page1_1);
	$page2_2->setParent($page1_1);

	$site1->addPage($page);
	$site1->addPage($page1_1);
	$site1->addPage($page1_2);
	$site1->addPage($page2_1);
	$site1->addPage($page2_2);


	$this->em->persist($page);
	$this->em->persist($page1_1);
	$this->em->persist($page1_2);
	$this->em->persist($page2_1);
	$this->em->persist($page2_2);

	// Add components
	$twitter = new Twitter('<i>tweet:-)</i><br/>');
	$facebook = new Facebook('<b>Face!</b><br/>', '45644523');

	$node1 = new Node($twitter);
	$node2 = new Node($facebook);
	$node2->setParent($node1);

	$page->addNode($node1);
	$page->addNode($node2);

	$this->em->persist($twitter);
	$this->em->persist($facebook);
	$this->em->persist($node1);
	$this->em->persist($node2);

	$this->em->flush();

	return 'Seeding done' . PHP_EOL;
    }

}