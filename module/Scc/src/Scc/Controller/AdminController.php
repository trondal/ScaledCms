<?php

namespace Scc\Controller;

use Doctrine\Common\Util\Debug;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Scc\Entity\Node;
use Scc\Entity\Page;
use Scc\Entity\Site;
use Scc\Entity\Twitter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class AdminController extends AbstractActionController implements ResourceInterface {

    public function getResourceId() {
        return __CLASS__;
    }

    public function indexAction() {
        $this->layout()->setTemplate('layout/admin');
    }

    public function siteAction() {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        
        $form = new \Scc\Form\SiteForm($this->getServiceLocator());
        $site = $em->getRepository('Scc\Entity\Site')->find(1);
        $form->bind($site);
        
        return array('form' => $form);
    }
    
    public function getAction() {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $form = new \Scc\Form\NodeForm($this->getServiceLocator());
        
        $node = $em->getRepository('Scc\Entity\Node')->find(2);
        $form->bind($node);
        
        $model = new \Zend\View\Model\ViewModel(array(
            'form' =>$form
        ));
        $model->setTemplate('scc/admin/site');
        return $model;
    }
    
    public function setAction() {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        
        $hydrator = new TwitterHydrator($em, 'Scc\Entity\Node');
        $node = new Node();
        $twitter = new Twitter('twitter');
        
        $data = array(
            'twitter' => $twitter
        );
        $node = $hydrator->hydrate($data, $node);
        
        echo '<pre>';
        \Doctrine\Common\Util\Debug::dump($node);
        exit;
        
        
        
        $hydrator = new DoctrineHydrator($em, 'Scc\Entity\Site');
        
        $site = new Site('site title', 'site slug');
        $pages = array();
        $page1 = new Page('page 1 title', 'page 1 slug');
        $pages[] = $page1;
        $page2 = new Page('page 2 title', 'page 2 slug');
        $pages[] = $page2;
        $data = array(
            'pages' => array($page1, $page2) //$pages // Note that you can mix integers and entities without any problem
        );
        $site = $hydrator->hydrate($data, $site);
        echo $site->getTitle(); // prints "The best blog post in the world !"
        echo count($site->getPages()); // prints 2      
        echo '<pre>';
        Debug::dump($site->getPages());
        exit;
        /* $sm = $this->getServiceLocator();
          $em = $sm->get('Doctrine\ORM\EntityManager');

          $node = $em->getRepository('Scc\Entity\Node')->find(4);

          $form = new \Scc\Form\NodeForm($sm);
          $form->bind($node);
          return array('form' => $form); */
    }

}