<?php

namespace Scc\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use PhlyRestfully\ResourceEvent;
use Scc\Controller\AuthServiceAware;
use Scc\Controller\EntityManagerAware;
use Scc\Entity\AuthAttempt;
use Zend\Authentication\AuthenticationService;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Http\PhpEnvironment\RemoteAddress;
use Zend\Paginator\Paginator;

class LoginService implements EntityManagerAware, ListenerAggregateInterface, AuthServiceAware, AuthAttemptServiceAware {

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     *
     * @var AuthenticationService
     */
    protected $authService;

    protected $listeners = array();

    protected $authAttemptService;

    public function setEntityManager(EntityManager $em) {
        $this->em = $em;
    }

    public function setAuthService(AuthenticationService $authService) {
        $this->authService = $authService;
    }

    public function setAuthAttemptService(AuthAttemptService $authAttemptService) {
        $this->authAttemptService = $authAttemptService;
    }

    public function attach(EventManagerInterface $events) {
        $events->attach('create', array($this, 'onCreate'));
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
        $data = $e->getParam('data', false);

        $adapter = $this->authService->getAdapter();
	$adapter->setIdentityValue($data->username);
	$adapter->setCredentialValue($data->password);
	$result = $this->authService->authenticate();

        if ($result->isValid()) {
            // Authentication ok
            // TODO: should use a logging scheme of some kind.
            $identity = $result->getIdentity();
            $this->authService->getStorage()->write($identity);

            $env = new RemoteAddress();
            $address = $env->getIpAddress();

            $authAttempt = new AuthAttempt($result->getIdentity(), new \DateTime(), $result->getCode(), $address);
            $this->authAttemptService->save($authAttempt);
            return $authAttempt;
        }
        // TODO: does not return anything if not ok.
    }

    public function onDelete($e) {
        $this->authService->clearIdentity();
        return true;
    }

    public function onFetch($e) {
        $this->authService->getIdentity()->getId();

        if (false === $id = $e->getParam('id', false)) {
            return false;
        }

        $repo = $this->em->getRepository('Scc\Entity\AuthAttempt');
        return $repo->findOneBy(array('id' => $id));
    }

    public function onFetchAll(ResourceEvent $e) {
        $dql = $this->em->createQuery('SELECT a FROM \Scc\Entity\AuthAttempt a
            WHERE a.user = :user ORDER BY a.time DESC')
            ->setParameter('user', $this->authService->getIdentity());
        $adapter = new DoctrineAdapter(new ORMPaginator($dql));

        $paginator = new Paginator($adapter);
        return $paginator;
    }

}