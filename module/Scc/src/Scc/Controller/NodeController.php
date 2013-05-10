<?php

namespace Scc\Controller;

use Scc\Service\NodeService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class NodeController extends AbstractActionController implements NodeServiceAware {

    /**
     * @var NodeService
     */
    protected $nodeService;

    public function setNodeService(NodeService $nodeService) {
        $this->nodeService = $nodeService;
    }

    public function indexAction() {
        $id = $this->params()->fromRoute('id');

        $startNode = $this->nodeService->find($id);

        $nodeArray = $this->nodeService->getChildren($startNode, false, null, 'ASC', true);

        // Load the components for all nodes
        $serviceLocator = $this->getServiceLocator();
        foreach ($nodeArray as $node) {
            $service = $serviceLocator->get($node->getClassName());
            $node->loadComponent($service->findOneByNode($node));
        }

        $view = new ViewModel(array(
	    'node' => $startNode
	));

	return $view;
    }
}