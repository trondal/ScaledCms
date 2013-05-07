<?php

namespace Scc\Form;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Scc\Form\Fieldset\PageFieldset;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceManager;

class PageForm extends Form {

    public function __construct(ServiceManager $services) {
        parent::__construct('create-page');
        $em = $services->get('Doctrine\ORM\EntityManager');
        
        $this->setHydrator(new DoctrineObject($em, 'Scc\Entity\Page'));
        
        $pageFieldset = new PageFieldset($services);
        $pageFieldset->setName('page');
        $pageFieldset->setUseAsBaseFieldset(true);
        $this->add($pageFieldset);
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Submit',
            'name' => 'submit',
            'attributes' => array(
                'value' => 'send'
            ))
        );
    }

}