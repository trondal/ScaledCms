<?php

namespace Scc\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use RuntimeException;
use Scc\Entity\Contact;
use Scc\Entity\HostName;
use Scc\Entity\Node;
use Scc\Entity\Page;
use Scc\Entity\Panel;
use Scc\Entity\Site;
use Scc\Entity\Twitter;
use Scc\Entity\User;
use Zend\Console\Request as ConsoleRequest;
use Zend\Crypt\Password\Bcrypt;
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

    public function createAction() {
	$request = $this->getRequest();
	if (!$request instanceof ConsoleRequest){
            throw new RuntimeException('You can only use this action from a console!');
        }

	$tool = new SchemaTool($this->em);
	$metaData = $this->em->getMetadataFactory()->getAllMetadata();

	$tool->createSchema($metaData);
	return 'Database created.' . PHP_EOL;
    }

    public function dropAction() {
	$request = $this->getRequest();
	if (!$request instanceof ConsoleRequest){
            throw new RuntimeException('You can only use this action from a console!');
        }

	$tool = new SchemaTool($this->em);
	$metaData = $this->em->getMetadataFactory()->getAllMetadata();

	$tool->dropSchema($metaData);
	return 'Database dropped.' . PHP_EOL;
    }



    public function rebuildAction() {
	echo $this->dropAction();
	echo $this->createAction();

	$request = $this->getRequest();
	if (!$request instanceof ConsoleRequest){
            throw new RuntimeException('You can only use this action from a console!');
        }

	// Add user and site
	$bcrypt = new Bcrypt();
	$user1 = new User('Alice', $bcrypt->create('password'), 'alice@gmail.com');
	$user2 = new User('Bob', $bcrypt->create('password'), 'bob@gmail.com');

	$site1 = new Site('Alice\'s first site', 'alice');
	$site2 = new Site('Alice\'s second site', 'alice2');
	$site3 = new Site('Bob\'s site', 'bob');

        $hostName1 = new HostName('alice.scaledcms.trondal');
        $site1->addHostName($hostName1);
        
        $hostName2 = new HostName('alice2.scaledcms.trondal');
        $site1->addHostName($hostName2);
        
        $hostName3 = new HostName('alice.test.scaledcms.trondal');
        $site2->addHostName($hostName3);
        
        $hostName4 = new HostName('bob.scaledcms.trondal');
        $site3->addHostName($hostName4);
        
	$user1->addSite($site1);
	$user1->addSite($site2);
	$user2->addSite($site3);

	$this->em->persist($user1);
	$this->em->persist($user2);
	$this->em->persist($site1);
	$this->em->persist($site2);
	$this->em->persist($site3);
        
        $this->em->persist($hostName1);
        $this->em->persist($hostName2);
        $this->em->persist($hostName3);
        $this->em->persist($hostName4);

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
        $panel = new Panel();
	$twitter1 = new Twitter('<i>tweet:-)</i>');
        $twitter2 = new Twitter('<b>BOBBO</b>');
	$contact = new Contact('Send us a message', 'trond.albinussen@gmail.com', 'Message sent.');

	//$board = new \MessageBoard\Entity\Board('My message board');

	//$message1 = new \MessageBoard\Entity\Message('Alice Jones', 'Alice says hi', 'Hi from Alice');
	//$message2 = new \MessageBoard\Entity\Message('Bob Olsen', 'Bob says hi', 'Hi from Bob');

	//$this->em->persist($message1);
	//$this->em->persist($message2);

	//$board->addMessage($message1);
	//$board->addMessage($message2);

	$node1 = new Node($panel);
        $node2 = new Node($twitter1);
	$node3 = new Node($twitter2);
        $node4 = new Node($contact);
        
	//$node3 = new Node($board);

	$node2->setParent($node1);
        $node3->setParent($node1);
        $node4->setParent($node1);
	//$node3->setParent($node1);

	$page->addNode($node1);
	$page->addNode($node2);
        $page->addNode($node3);
	$page->addNode($node4);

	$this->em->persist($twitter1);
        $this->em->persist($twitter2);
	$this->em->persist($contact);
	$this->em->persist($panel);
        
	$this->em->persist($node1);
	$this->em->persist($node2);
	$this->em->persist($node3);
        $this->em->persist($node4);

        /** FOO */
        $status1 = new \StatusApi\Entity\Status('status', 1366808961, 'trond', 'text1');
        $status2 = new \StatusApi\Entity\Status('status', 1366808962, 'trond', 'text2');
        $status3 = new \StatusApi\Entity\Status('status', 1366808963, 'trond', 'text3');
        $status4 = new \StatusApi\Entity\Status('status', 1366808964, 'trond', 'text4');
        $status5 = new \StatusApi\Entity\Status('status', 1366808965, 'trond', 'text5');
        $status6 = new \StatusApi\Entity\Status('status', 1366808966, 'trond', 'text6');
        $status7 = new \StatusApi\Entity\Status('status', 1366808967, 'trond', 'text7');
        $status8 = new \StatusApi\Entity\Status('status', 1366808968, 'rob', 'text8');
        
        $this->em->persist($status1);
        $this->em->persist($status2);
        $this->em->persist($status3);
        $this->em->persist($status4);
        $this->em->persist($status5);
        $this->em->persist($status6);
        $this->em->persist($status7);
        $this->em->persist($status8);
        
        $this->em->flush();
        
	return 'Database populated' . PHP_EOL;
    }

}