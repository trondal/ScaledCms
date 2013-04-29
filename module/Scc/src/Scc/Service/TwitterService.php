<?php

namespace Scc\Service;

use Doctrine\ORM\EntityManager;
use Scc\Controller\EntityManagerAware;
use Scc\Entity\Node;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class TwitterService implements EntityManagerAware, ListenerAggregateInterface {

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    protected $listeners = array();

    public function setEntityManager(EntityManager $em) {
        $this->em = $em;
    }

    public function findOneByNode(Node $node) {
        $repo = $this->em->getRepository('Scc\Entity\Twitter');
        return $repo->findOneByNode($node);
    }

    public function attach(EventManagerInterface $events) {
        $events->attach('create', array($this, 'onCreate'));
        $events->attach('update', array($this, 'onUpdate'));
        $events->attach('patch', array($this, 'onPatch'));
        $events->attach('delete', array($this, 'onDelete'));
        $events->attach('fetch', array($this, 'onFetch'));
        $events->attach('fetchAll', array($this, 'onFetchAll'));
    }

    public function detach(EventManagerInterface $events) {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }
    
    public function onCreate($e) {
        echo '<pre>';
        var_dump('POST');
        exit;
    }
    
    public function onUpdate($e) {
        echo '<pre>';
        var_dump('UPDATe');
        exit;
    }

    public function onPatch($e) {
        echo '<pre>';
        var_dump('PATHC');
        exit;
    }
    
    public function onDelete($e) {
        echo '<pre>';
        var_dump('DELETE');
        exit;
    }
    
    public function onFetch($e) {
        if (false === $id = $e->getParam('id', false)) {
            return false;
        }
        $repo = $this->em->getRepository('Scc\Entity\Twitter');
        return $repo->findOneBy(array('id' => $id));
    }
    
    public function onFetchAll($e) {
        $repo = $this->em->getRepository('Scc\Entity\Twitter');
        return $repo->findAll();
    }
    
}