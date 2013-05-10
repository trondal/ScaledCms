<?php

namespace Scc\View\Helper;

use Scc\Entity\Node;
use Zend\Http\Request;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class ContactHelper extends AbstractHelper {

    /**
     *
     * @var Request
     */
    protected $request;

    public function setRequest(Request $request) {
	$this->request = $request;
    }

    public function __invoke(Node $node) {
        $component = $node->getComponent();
	if ($this->request->isPost()) {
            $component->setMsg($this->request->getPost('email'));
	}

	$model = new ViewModel(array('component' => $component));
	$model->setTemplate('scc/contact/index');
	return $this->getView()->render($model);
    }

}