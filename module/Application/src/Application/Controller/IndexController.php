<?php

namespace Application\Controller;

use Application\Service\PageService;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
    implements ResourceInterface, PageServiceAware {

    /**
     *
     * @var PageService
     */
    protected $pageService;

    public function setPageService(PageService $pageService){
	$this->pageService = $pageService;
    }

    public function getResourceId() {
	return __CLASS__;
    }

    public function indexAction() {
	$ary[] = $this->params()->fromRoute('a');
	$ary[] = $this->params()->fromRoute('b');
	$ary[] = $this->params()->fromRoute('c');
	$ary[] = $this->params()->fromRoute('d');

	$paths = array_filter($ary);

	$page = $this->pageService->findByMaterializedPath($paths);

	$view = new ViewModel(array(
	    'page' => $page,
	    'path' => $this->pageService->getMaterializedPath($page)
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