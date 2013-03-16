<?php

namespace Scc\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class LoginController extends AbstractActionController implements ResourceInterface, AuthServiceAware {

    /**
     * @var \Zend\Authentication\AuthenticationService
     */
    protected $authService;

    public function getResourceId() {
	return __CLASS__;
    }

    public function setAuthService(\Zend\Authentication\AuthenticationService $authService) {
	$this->authService = $authService;
    }

    public function indexAction() {
	$form = $this->getServiceLocator()->get('Scc\Form\LoginForm');

	$request = $this->getRequest();

	if ($request->isPost()) {
	    $form->setData($request->getPost());

	    $adapter = $this->authService->getAdapter();
	    $adapter->setIdentityValue($this->params()->fromPost('username'));
	    $adapter->setCredentialValue($this->params()->fromPost('password'));
	    $authResult = $this->authService->authenticate();

	    if ($authResult->isValid()) {
		return $this->redirect()->toRoute('admin/admin-segment');
	    }
	}

	return array('form' => $form);
    }

}