<?php

namespace Application\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController implements EntityManagerAware {

    /**
     * @var EntityManager
     */
    protected $em;

    public function setEntityManager(EntityManager $em) {
        $this->em = $em;
    }

    public function indexAction() {

        $indexPage = $this->em->find('Application\Entity\Page', 1);
        //$this->displayTree($indexPage);

        /*$clonePage = clone $indexPage;
        $clonePage->cloneChildren();
        $this->em->persist($clonePage);
        $this->em->flush();*/

        return new ViewModel(array(
            'page' => $indexPage
        ));
    }



}