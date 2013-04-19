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

        $locator = $this->getServiceLocator();
        foreach ($page->getNodes() as $node) {
            $service = $locator->get($node->getClassName());
            $node->loadComponent($service->findOneByNode($node));
        }
        $nodes = $page->getNodes();

	/*$components = array();
	foreach ($page->getNodes() as $node) {
	    $components[] = $this->nodeService->findByNode($node);
	}*/
                
	$view = new ViewModel(array(
	    'page' => $page,
	    'path' => $this->pageService->getMaterializedPath($page),
	    'node' => $nodes[0]
	));
        
        // first node is always connected to an panel
        $view->setTemplate('scc/panel/index');
	return $view;
    }

}