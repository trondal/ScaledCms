<?php

namespace ApplicationTest\Service;

use Application\Entity\Page;
use Application\Entity\Site;
use Application\Entity\User;
use Application\Service\PageService;
use ApplicationTest\HttpControllerTestCase;

class PageTest extends HttpControllerTestCase {

    /**
     * @var PageService
     */
    protected $pageService;

    public function setUp() {
	parent::setUp();

	$this->pageService = $this->sm->get('Application\Service\PageService');
	$this->populate();
    }

    private function populate(){
	$user1 = new User('user1', 'password1', 'email@snakeoil.com');
	$site1 = new Site('site1');
	$user1->addSite($site1);

	$page1 = new Page('page1');
	$page2 = new Page('page2');
	$page3 = new Page('page3');
	$page4 = new Page('page4');
	$page5 = new Page('page5');

	$repo = $this->em->getRepository('Application\Entity\Page');
	$repo->persistAsFirstChild($page1);
	$repo->persistAsFirstChildOf($page2, $page1);
	$repo->persistAsLastChildOf($page3, $page1);
	$repo->persistAsFirstChildOf($page4, $page3);
	$repo->persistAsLastChildOf($page5, $page3);

	$this->em->persist($user1);
	$this->em->persist($site1);
	$this->em->flush();
	$this->em->clear(); //-> important!
    }

    /**
     * @test
     */
    public function assertPopulate(){
	$repo = $this->em->getRepository('Application\Entity\Page');
	$page1 = $repo->findOneByTitle('page1');
	$page2 = $repo->findOneByTitle('page2');
	$page3 = $repo->findOneByTitle('page3');
	$page4 = $repo->findOneByTitle('page4');
	$page5 = $repo->findOneByTitle('page5');

	$this->assertEquals(1, $page1->getLeft());
	$this->assertEquals(10, $page1->getRight());
	$this->assertEquals(2, $page2->getLeft());
	$this->assertEquals(3, $page2->getRight());
	$this->assertEquals(4, $page3->getLeft());
	$this->assertEquals(9, $page3->getRight());
	$this->assertEquals(5, $page4->getLeft());
	$this->assertEquals(6, $page4->getRight());
	$this->assertEquals(7, $page5->getLeft());
	$this->assertEquals(8, $page5->getRight());
    }

    /**
     * @test
     */
    public function persistMoveUp() {
	$repo = $this->em->getRepository('Application\Entity\Page');

	$page5 = $repo->findOneByTitle('page5');
	$page4 = $repo->findOneByTitle('page4');

	$this->assertEquals(5, $page4->getLeft());
	$this->assertEquals(6, $page4->getRight());

	$this->assertEquals(7, $page5->getLeft());
	$this->assertEquals(8, $page5->getRight());

	$this->pageService->persistMoveUp($page5, 1);

	$this->assertEquals(5, $page5->getLeft());
	$this->assertEquals(6, $page5->getRight());
	$this->assertEquals(7, $page4->getLeft());
	$this->assertEquals(8, $page4->getRight());
    }

    /**
     * @test
     */
    public function persistMoveDown(){
	$repo = $this->em->getRepository('Application\Entity\Page');
	$page4 = $repo->findOneByTitle('page4');

	$this->assertEquals(5, $page4->getLeft());
	$this->assertEquals(6, $page4->getRight());

	$this->pageService->persistMoveDown($page4, 1);

	$this->assertEquals(7, $page4->getLeft());
	$this->assertEquals(8, $page4->getRight());
    }

    /**
     * @test
     */
    public function persistDeleteWithCascade() {
	$repo = $this->em->getRepository('Application\Entity\Page');
	$page3 = $repo->findOneByTitle('page3');

	$this->pageService->persistDelete($page3);

	$page1 = $repo->findOneByTitle('page1');
	$page2 = $repo->findOneByTitle('page2');

	$this->assertEquals(1, $page1->getLeft());
	$this->assertEquals(4, $page1->getRight());

	$this->assertEquals(2, $page2->getLeft());
	$this->assertEquals(3, $page2->getRight());

	$this->assertNull($repo->findOneByTitle('page3'));
	$this->assertNull($repo->findOneByTitle('page4'));
	$this->assertNull($repo->findOneByTitle('page5'));
    }

}