<?php

namespace Scc\Form;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Scc\Form\Fieldset\NodeFieldset;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceManager;

class NodeForm extends Form {

    public function __construct(ServiceManager $services) {
        parent::__construct('create-node');
        $em = $services->get('Doctrine\ORM\EntityManager');
        
        $this->setHydrator(new DoctrineHydrator($em, 'Scc\Entity\Node'));
        
        $nodeFieldset = new NodeFieldset($services);
        $nodeFieldset->setName('nodes');
        $nodeFieldset->setUseAsBaseFieldset(true);
        $this->add($nodeFieldset);
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Submit',
            'name' => 'submit',
            'attributes' => array(
                'value' => 'send'
            ))
        );
    }

}