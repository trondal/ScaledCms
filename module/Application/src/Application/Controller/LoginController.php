<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class LoginController extends AbstractActionController implements ResourceInterface {

    public function getResourceId() {
	return __CLASS__;
    }

    public function indexAction() {
	$form = $this->getServiceLocator()->get('Application\Form\LoginForm');

	$request = $this->getRequest();

	if ($request->isPost()) {
            //$album = new Album();
            //$form->setInputFilter($album->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
		echo '<pre>';
		var_dump($form->getData());
		exit;

		//$album->exchangeArray($form->getData());
                //$this->getAlbumTable()->saveAlbum($album);

                // Redirect to list of albums
                //return $this->redirect()->toRoute('album');
            }
        }

	return array('form' => $form);
    }

}