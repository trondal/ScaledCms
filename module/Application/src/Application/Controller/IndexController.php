<?php

namespace Application\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController implements EntityManagerAware, ResourceInterface {

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

    public function indexAction() {
	$id = $this->getRequest()->getQuery('id');
	$page = $this->em->find('Application\Entity\Page', $id);

	$view = new ViewModel(array(
	    'page' => $page
	));

	foreach ($page->getNodes() as $node) {
	    $component = $node->getComponent();
	    $controllerKey = 'Application\Controller\\' . $component->getClassName();

	    $componentView = $this->forward()->dispatch($controllerKey, array(
		'controller' => $controllerKey,
		'action' => 'index',
		'component' => $component
	    ));

	    $view->addChild($componentView, 'components', true);
	}
	return $view;
    }

}