<?php

namespace MessageBoard\View\Helper;

use MessageBoard\Entity\Board as BoardEntity;
use MessageBoard\Form\EditBoardForm;
use MessageBoard\Service\MessageService;
use Zend\Http\PhpEnvironment\Request;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class Board extends AbstractHelper {

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

    public function handle(BoardEntity $board) {
	if ($this->request->getQuery('editboard') == '1') {

	} else {
	    return $this->newMessageAction($board);
	}
    }

    public function newMessageAction(BoardMessage $message) {
	$form = new \MessageBoard\Form\NewMessageForm($this->serviceLocator);
	$form->bind($message);
	if ($this->request->isPost()) {
	    $form->setData($this->request->getPost());
	    if ($form->isValid()) {
		$boardService = $this->serviceLocator->get('MessageBoard\Service\BoardService');
		$boardService->save($board);
	    }
	}
    }

    public function editBoard(BoardEntity $board) {
	$form = new EditBoardForm($this->serviceLocator);
	$form->bind($board);
	if ($this->request->isPost()) {
	    $form->setData($this->request->getPost());
	    if ($form->isValid()) {
		$boardService = $this->serviceLocator->get('MessageBoard\Service\BoardService');
		$boardService->save($board);
	    }
	}
    }

    public function listBoard(BoardEntity $board) {
	$model = new ViewModel(array('board' => $board));
	$model->setTemplate('messageboard/board/list');
	return $this->getView()->render($model);
    }

}