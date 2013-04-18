<?php

namespace MessageBoard\View\Helper;

use MessageBoard\Entity\Message as MessageEntity;
use MessageBoard\Form\NewMessageForm;
use MessageBoard\Service\MessageService;
use Zend\Http\PhpEnvironment\Request;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class Message extends AbstractHelper {

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    public function setMessageService(MessageService $service) {
	$this->messageService = $service;
    }

    public function __construct(ServiceLocatorInterface $sm) {
	$this->serviceLocator = $sm;
	$this->request = $sm->get('Request');
    }

    public function handle(MessageEntity $message) {
	if ($this->request->getQuery('editboard') == '1') {

	} else {
	    return $this->newMessageAction($message);
	}
    }

    public function newMessage(BoardMessage $message) {
	$form = new NewMessageForm($this->serviceLocator);
	$form->bind($message);
	if ($this->request->isPost()) {
	    $form->setData($this->request->getPost());
	    if ($form->isValid()) {
		$boardService = $this->serviceLocator->get('MessageBoard\Service\MessageService');
		$boardService->save($message);
	    }
	}
    }

    public function editMessage(BoardMessage $message) {
	$form = new EditMessageForm($this->serviceLocator);
	$form->bind($message);
	if ($this->request->isPost()) {
	    $form->setData($this->request->getPost());
	    if ($form->isValid()) {
		$messageService = $this->serviceLocator->get('MessageBoard\Service\MessageService');
		$messageService->save($message);
	    }
	}
    }

    public function listMessage(BoardMessage $message) {
	$model = new ViewModel(array('message' => $message));
	$model->setTemplate('messageboard/message/list');
	return $this->getView()->render($model);
    }

}