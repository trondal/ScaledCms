<?php

namespace StatusApi;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Paginator\Paginator;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class StatusDbTableGateway extends AbstractTableGateway implements \Scc\Controller\EntityManagerAware {

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    public function setEntityManager(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
    }

    public function __construct(Adapter $adapter, $table = 'status') {
        $this->adapter = $adapter;
        $this->table = $table;
        $rowPrototype = new \StatusApi\Entity\Status();
        $hydratorPrototype = new ClassMethodsHydrator();
        $this->resultSetPrototype = new HydratingResultSet($hydratorPrototype, $rowPrototype);
        $this->resultSetPrototype->buffer();
        $this->initialize();
    }

    public function fetchAll($user = null) {     
        $query = $this->em->createQuery('SELECT s FROM \StatusApi\Entity\Status AS s
          WHERE s.user = :user ORDER BY s.timestamp ASC')
          ->setParameter('user', $user);
        
        $adapter  = new \DoctrineORMModule\Paginator\Adapter\DoctrinePaginator(new \Doctrine\ORM\Tools\Pagination\Paginator($query));
        return new Paginator($adapter);
    }

}