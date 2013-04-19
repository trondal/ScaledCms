<?php

namespace Scc\View\Helper;

use Scc\Entity\Contact as ContactEntity;
use Zend\Http\Request;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class Contact extends AbstractHelper implements \Scc\Controller\EntityManagerAware {

    /**
     *
     * @var \Zend\Http\PhpEnvironment\Request
     */
    protected $request;
    protected $em;

    public function setEntityManager(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
    }

    public function setRequest(Request $request) {
	$this->request = $request;
    }

    public function __invoke(\Scc\Entity\Contact $contact) {
        //$repo = $this->em->getRepository($node->getClassName());
        //$contact = $repo->findOneBy(array('node' => $node->getId()));
        
	if ($this->request->isPost()) {
            $contact->setMsg($this->request->getPost('email'));
	}

	$model = new ViewModel(array('component' => $contact));
	$model->setTemplate('scc/contact/index');
	return $this->getView()->render($model);
    }

}