<?php

namespace Application\Service;

use Application\Controller\EntityManagerAware;
use Application\Entity\Page;
use Doctrine\ORM\EntityManager;

class PageService implements EntityManagerAware {

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function setEntityManager(EntityManager $em) {
	$this->em = $em;
    }

    public function persistMoveUp(Page $page, $number = 1) {
	$repo = $this->em->getRepository('Application\Entity\Page');
	$repo->moveUp($page, $number);
    }

    public function persistMoveDown(Page $page, $number = 1) {
	$repo = $this->em->getRepository('Application\Entity\Page');
	$repo->moveDown($page, $number);
    }

    public function persistDelete(Page $page, $cascade = true) {
	if ($cascade) {
	    $this->em->remove($page);
	    $this->em->flush();
	} else {
	    $repo = $this->em->getRepository('Application\Entity\Page');
	    $repo->removeFromTree($page);
	}
    }

    /**
     * @param \Application\Entity\Page $page
     * @throws \Exception
     */
    public function persistAsFirstChild(\Application\Entity\Page $page) {
	throw new \Exception('Not implemented');
    }

    /**
     * @param \Application\Entity\Page $node
     * @param \Application\Entity\Page $parent
     * @throws \Exception
     */
    public function persistAsFirstChildOf(\Application\Entity\Page $node, \Application\Entity\Page $parent) {
	throw new \Exception('Not implemented');
    }

    /**
     * @param \Application\Entity\Page $node
     * @throws \Exception
     */
    public function persistAsLastChild(\Application\Entity\Page $node) {
	throw new \Exception('Not implemented');
    }

    /**
     * @param \Application\Entity\Page $node
     * @param \Application\Entity\Page $parent
     * @throws \Exception
     */
    public function persistAsLastChildOf(\Application\Entity\Page $node, \Application\Entity\Page $parent) {
	throw new \Exception('Not implemented');
    }

    /**
     * @param \Application\Entity\Page $node
     * @throws \Exception
     */
    public function persistAsNextSibling(\Application\Entity\Page $node) {
	throw new \Exception('Not implemented');
    }

    /**
     * @param \Application\Entity\Page $node
     * @param \Application\Entity\Page $sibling
     * @throws \Exception
     */
    public function persistAsNextSiblingOf(\Application\Entity\Page $node, \Application\Entity\Page $sibling) {
	throw new \Exception('Not implemented');
    }

    /**
     * @param \Application\Entity\Page $node
     * @throws \Exception
     */
    public function persistAsPrevSibling(\Application\Entity\Page $node) {
	throw new \Exception('Not implemented');
    }

    /**
     * @param \Application\Entity\Page $node
     * @param \Application\Entity\Page $sibling
     * @throws \Exception
     */
    public function persistAsPrevSiblingOf(\Application\Entity\Page $node, \Application\Entity\Page $sibling) {
	throw new \Exception('Not implemented');
    }

}