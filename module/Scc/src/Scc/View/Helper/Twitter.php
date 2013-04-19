<?php

namespace Scc\View\Helper;

use Doctrine\ORM\EntityManager;
use Scc\Controller\EntityManagerAware;
use Scc\Entity\Node;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class Twitter extends AbstractHelper implements EntityManagerAware {

    /**
     * @var EntityManager
     */
    protected $em;
    
    public function setEntityManager(EntityManager $em) {
       $this->em = $em;
    }
    
    public function __invoke(\Scc\Entity\Twitter $twitter) {
        //$repo = $this->em->getRepository($node->getClassName());
        
        //$twitter = $repo->findOneBy(array('node' => $node->getId()));
        
	$model = new ViewModel(array('component' => $twitter));
	$model->setTemplate('scc/twitter/index');
	return $this->getView()->render($model);
    }

}