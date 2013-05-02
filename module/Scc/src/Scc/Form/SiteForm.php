<?php

namespace Scc\Form;

use Scc\Controller\ComponentHydrator;
use Scc\Form\Fieldset\SiteFieldset;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class SiteForm extends Form {
    
    public function __construct(ServiceManager $services) {
        parent::__construct('site');
        $em = $services->get('Doctrine\ORM\EntityManager');
        
        $this->setHydrator(new DoctrineObject($em, 'Scc\Entity\Site'));
        
        $siteFieldset = new SiteFieldset($services);
        $siteFieldset->setName('sites');
        $siteFieldset->setUseAsBaseFieldset(true);
        $this->add($siteFieldset);
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Submit',
            'name' => 'submit',
            'attributes' => array(
                'value' => 'send'
            ))
        );
    }
    
}