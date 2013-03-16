<?php

namespace Scc\Controller;

use Scc\Service\NodeService;
use Scc\Service\PageService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
    implements ResourceInterface, PageServiceAware, NodeServiceAware {

    /**
     * @var PageService
     */
    protected $pageService;

    /**
     * @var NodeService
     */
    protected $nodeService;

    public function setPageService(PageService $pageService){
	$this->pageService = $pageService;
    }

    public function setNodeService(NodeService $nodeService) {
	$this->nodeService = $nodeService;
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

	if (!$page) {
	    $this->getResponse()-> setStatusCode(404);
	    return;
	}

	$view = new ViewModel(array(
	    'page' => $page,
	    'path' => $this->pageService->getMaterializedPath($page)
	));

	foreach ($page->getNodes() as $node) {
	    $component = $this->nodeService->findByNode($node);
	    $controllerKey = 'Scc\Controller\\' . $component->getClassName();

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