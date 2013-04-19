<?php

namespace MessageBoard\Controller;

use MessageBoard\Entity\Board;
use MessageBoard\Form\EditBoardForm;
use MessageBoard\Form\NewBoardForm;
use MessageBoard\Service\BoardServiceAware;
use Scc\Service\User;
use Scc\Service\UserServiceAware;
use Zend\Mvc\Controller\AbstractActionController;

class BoardController extends AbstractActionController implements UserServiceAware, BoardServiceAware {

    /**
     * @var \Scc\Service\User
     */
    protected $userService;

    /**
     *
     * @var \MessageBoard\Service\BoardService
     */
    protected $boardService;

    public function setUserService(User $userService) {
	$this->userService = $userService;
    }

    public function setBoardService(\MessageBoard\Service\BoardService $service) {
	$this->boardService = $service;
    }

    /**
     * List all boards for user
     * @return type
     */
    public function indexAction() {
	$user = $this->userService->find(1);
	$boards = $this->boardService->findByUser($user);

	$view = new \Zend\View\Model\ViewModel(array('boards' => $boards));
	$view->setTemplate('board/list');
	return $view;
    }

    public function boardAction() {
	$id = $this->params()->fromRoute('id');
    }

    public function newAction() {

	$form = new NewBoardForm($this->serviceLocator);
	$board = new Board('');
	$form->bind($board);

	if ($this->request->isPost()) {
	    $form->setData($this->request->getPost());
	    if ($form->isValid()) {
		$em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$user = $em->find('Scc\Entity\User', 1);
		//$board->
		$boardService = $this->getServiceLocator()->get('MessageBoard\Service\BoardService');
		$boardService->save($board);
	    }
	}

	return array('form' => $form);
    }

    public function editAction() {
	$id = $this->params()->fromRoute('id');

	$boardService = $this->getServiceLocator()->get('MessageBoard\Service\BoardService');
	$board = $boardService->find($id);
	$form = new EditBoardForm($this->serviceLocator);
	$form->bind($board);
	if ($this->request->isPost()) {
	    $form->setData($this->request->getPost());
	    if ($form->isValid()) {
		$boardService = $this->serviceLocator->get('MessageBoard\Service\BoardService');
		$boardService->save($board);
	    }
	}
	return array('form' => $form);
    }

    public function deleteAction() {

    }

    /* public function processAction() {
      if (!$this->request->isPost()) {
      return $this->redirect()->toRoute('contact');
      }

      $post = $this->request->getPost();
      $comment = $this->getCommentService()->addComment($post);

      if ($comment == FALSE) {
      $this->flashMessenger()->addMessage('Problem with Comment');
      return $this->redirect()->toUrl($post->url);
      }

      $this->flashMessenger()->addMessage('Comment success.');
      return $this->redirect()->toUrl($post->url);
      } */
}