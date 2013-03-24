<?php

namespace Scc\View\Helper;

use Scc\Entity\Contact as ContactEntity;
use Zend\Http\Request;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class Contact extends AbstractHelper {

    protected $request;

    public function __construct(Request $request) {
	$this->request = $request;
    }

    public function setRequest(Request $request) {
	$this->request = $request;
    }

    public function getRequest() {
	return $this->request;
    }

    public function handle(ContactEntity $contact) {
	if ($this->getRequest()->isPost()) {

	}

	$model = new ViewModel(array('component' => $contact));
	$model->setTemplate('scc/contact/index');
	return $this->getView()->render($model);
    }

}