<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class AdminController extends AbstractActionController implements ResourceInterface {

    public function getResourceId() {
	return __CLASS__;
    }

    public function indexAction() {

    }

}