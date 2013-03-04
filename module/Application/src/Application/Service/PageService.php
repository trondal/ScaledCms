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

    public function persistDelete(Page $page) {
	$this->em->remove($page);
	$this->em->flush();
    }

    /**
     * @param Page $page
     * @throws \Exception
     */
    public function persistAsFirstChild(Page $page) {
	throw new \Exception('Not implemented');
    }

    /**
     * @param Page $node
     * @param Page $parent
     * @throws \Exception
     */
    public function persistAsFirstChildOf(Page $node, Page $parent) {
	throw new \Exception('Not implemented');
    }

    /**
     * @param Page $node
     * @throws \Exception
     */
    public function persistAsLastChild(Page $node) {
	throw new \Exception('Not implemented');
    }

    /**
     * @param Page $node
     * @param Page $parent
     * @throws \Exception
     */
    public function persistAsLastChildOf(Page $node, Page $parent) {
	throw new \Exception('Not implemented');
    }

    /**
     * @param Page $node
     * @throws \Exception
     */
    public function persistAsNextSibling(Page $node) {
	throw new \Exception('Not implemented');
    }

    /**
     * @param Page $node
     * @param Page $sibling
     * @throws \Exception
     */
    public function persistAsNextSiblingOf(Page $node, Page $sibling) {
	throw new \Exception('Not implemented');
    }

    /**
     * @param Page $node
     * @throws \Exception
     */
    public function persistAsPrevSibling(Page $node) {
	throw new \Exception('Not implemented');
    }

    /**
     * @param Page $node
     * @param Page $sibling
     * @throws \Exception
     */
    public function persistAsPrevSiblingOf(Page $node, Page $sibling) {
	throw new \Exception('Not implemented');
    }

    public function getMaterializedPath(Page $page) {
	$array = $this->em->createQuery(
			'SELECT a.slug FROM Application\Entity\Page a, Application\Entity\Page c
	 WHERE c.lft >= a.lft AND c.rgt <= a.rgt
	 AND c.slug = :slug
	 ORDER BY a.lft'
		)->setParameter('slug', $page->getSlug())
		->getScalarResult();

	$ary = array();
	foreach ($array as $values) {
	    $ary[] = $values['slug'];
	}
	return implode('/', $ary);
    }

    public function findBySlug($slug) {
	$repo = $this->em->getRepository('Application\Entity\Page');
	return $repo->findOneBy(array('slug' => $slug));
    }

    public function findByMaterializedPath(array $paths) {
	$query = null;
	if (empty($paths)) {
	    $query = $this->em->createQuery(
		'SELECT a FROM Application\Entity\Page a, Application\Entity\Page c
		 WHERE c.lft >= a.lft AND c.rgt <= a.rgt AND c.slug IS NULL
		 AND c.slug IS NULL
		 ORDER BY a.lft DESC');
	} else {
	    $last = end($paths);
	    $query = $this->em->createQuery(
		'SELECT a FROM Application\Entity\Page a, Application\Entity\Page c
		 WHERE c.lft >= a.lft AND c.rgt <= a.rgt AND c.slug IN (:slugs)
		 AND c.slug = :slug
		 ORDER BY a.lft DESC
		')->setParameter(':slug', $last)
		->setParameter(':slugs', $paths);
	}
	$query->setMaxResults(1);
	$pages = $query->getResult();
	return $pages[0];
    }

}