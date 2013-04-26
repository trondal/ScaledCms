<?php

namespace StatusApi;

use Doctrine\ORM\EntityManager;
use PhlyRestfully\Exception\CreationException;
use PhlyRestfully\Exception\PatchException;
use PhlyRestfully\Exception\UpdateException;
use Scc\Controller\EntityManagerAware;
use StatusApi\Entity\Status;
use Zend\Db\Exception\ExceptionInterface as DbException;
use Zend\Db\TableGateway\TableGatewayInterface as TableGateway;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Stdlib\CallbackHandler;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class StatusDbPersistence implements ListenerAggregateInterface, StatusPersistenceInterface, EntityManagerAware {

    /**
     * @var ClassMethodsHydrator
     */
    protected $hydrator;

    /**
     * @var CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * @var TableGateway
     */
    protected $table;

    /**
     * User for whom to manipulate status; none removes ability to
     * create/update/patch/delete, but will retrieve any status by id, or a
     * list of all statuses from all users.
     * @var string
     */
    protected $user;

    /**
     * @var StatusValidator
     */
    protected $validator;
    
    protected $em;

    public function setEntityManager(EntityManager $em) {
        $this->em = $em;
    }

    public function __construct(TableGateway $table, $user = null) {
        $this->table = $table;
        $this->user = $user;
        $this->validator = new StatusValidator();
        $this->hydrator = new ClassMethodsHydrator();
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

    public function setUser($user) {
        if (empty($user)) {
            $this->user = null;
            return;
        }
        $this->user = (string) $user;
    }

    public function onCreate($e) {
        if (!$this->user) {
            throw new CreationException('User must be specified in order to create a status');
        }
        if (false === $data = $e->getParam('data', false)) {
            throw new CreationException('Missing data');
        }

        $data = (array) $data;

        // Inject user into data
        $data['user'] = $this->user;

        $status = new Status();
        $status = $this->hydrator->hydrate($data, $status);
        if (!$this->validator->isValid($status)) {
            throw new CreationException('Status failed validation');
        }
        $data = $this->hydrator->extract($status);
        try {
            $this->table->insert($data);
        } catch (DbException $exception) {
            throw new CreationException('DB exception when creating status', null, $exception);
        }

        return $data;
    }

    public function onUpdate($e) {
        if (!$this->user) {
            throw new UpdateException('User must be specified in order to update a status');
        }
        if (false === $id = $e->getParam('id', false)) {
            throw new UpdateException('Missing id');
        }

        if (false === $data = $e->getParam('data', false)) {
            throw new UpdateException('Missing data');
        }

        $data = (array) $data;
        
        $original = $this->em->getRepository('StatusApi\Entity\Status')->findOneBy(array('id' => $id));

        if (!$original) {
            throw new UpdateException('Cannot update; status not found', 404);
        }

        $hydrator = new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($this->em, 'StatusApi\Entity\Status');

        $updated = $hydrator->hydrate($data, $original);
        
        $this->em->persist($updated);
        $this->em->flush();

        return $updated;
    }

    public function onPatch($e) {
        if (!$this->user) {
            throw new PatchException('User must be specified in order to patch a status');
        }

        if (false === $id = $e->getParam('id', false)) {
            throw new PatchException('Missing id');
        }

        if (false === $data = $e->getParam('data', false)) {
            throw new PatchException('Missing data');
        }

        $data = (array) $data;
        $rowset = $this->table->select(array('id' => $id));
        $original = $rowset->current();
        if (!$original) {
            throw new PatchException('Cannot patch; status not found', 404);
        }

        $allowedUpdates = array(
            'type' => true,
            'text' => true,
            'image_url' => true,
            'link_url' => true,
            'link_title' => true,
        );
        $updates = array_intersect_key($data, $allowedUpdates);

        $status = $this->hydrator->hydrate($updates, $original);
        if (!$this->validator->isValid($status)) {
            throw new PatchException('Patched status failed validation');
        }

        try {
            $this->table->update($updates, array('id' => $id));
        } catch (DbException $exception) {
            throw new PatchException('DB exception when updating status', null, $exception);
        }

        return $status;
    }

    public function onDelete($e) {
        if (!$this->user) {
            return false;
        }
        if (false === $id = $e->getParam('id', false)) {
            return false;
        }
        if (!$this->table->delete(array('id' => $id))) {
            return false;
        }

        return true;
    }

    public function onFetch($e) {
        if (false === $id = $e->getParam('id', false)) {
            return false;
        }

        $criteria = array('id' => $id);
        if ($this->user) {
            $criteria['user'] = $this->user;
        }

        $rowset = $this->table->select($criteria);
        $item = $rowset->current();
        if (!$item) {
            return false;
        }
        return $item;
    }

    public function onFetchAll($e) {
        $result = $this->table->fetchAll($this->user);
        return $result;
    }

}