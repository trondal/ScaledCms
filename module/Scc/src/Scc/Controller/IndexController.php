<?php

namespace Scc\Controller;

use Scc\Service\NodeService;
use Scc\Service\PageService;
use Scc\Service\SiteService;
use Scc\Service\SiteServiceAware;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
    implements ResourceInterface, PageServiceAware, NodeServiceAware, SiteServiceAware {

    /**
     * @var PageService
     */
    protected $pageService;

    /**
     * @var NodeService
     */
    protected $nodeService;

    /**
     * @var SiteService
     */
    protected $siteService;

    public function setPageService(PageService $pageService){
	$this->pageService = $pageService;
    }

    public function setNodeService(NodeService $nodeService) {
	$this->nodeService = $nodeService;
    }

    public function setSiteService(SiteService $siteService) {
        $this->siteService = $siteService;
    }

    public function getResourceId() {
	return __CLASS__;
    }

    public function indexAction() {
        $serverUrl = $this->getServiceLocator()->get('viewhelpermanager')->get('ServerUrl');
        // Strip ports due to Varnish.
        $tmpArray = explode(':', $serverUrl->getHost());
        $hostName = $tmpArray[0];

	$ary[] = $this->params()->fromRoute('a');
	$ary[] = $this->params()->fromRoute('b');
	$ary[] = $this->params()->fromRoute('c');
	$ary[] = $this->params()->fromRoute('d');

	$paths = array_filter($ary);

        $site = $this->siteService->findOneByHostName($hostName);
	$page = $this->pageService->findByMaterializedPathAndSite($paths, $site);

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

	$view = new ViewModel(array(
	    'page' => $page,
	    'path' => $this->pageService->getMaterializedPath($page),
	    'node' => $nodes[0]
	));

	return $view;
    }

}