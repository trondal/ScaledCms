<?php

namespace Scc\Form\Fieldset;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class TwitterFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct(\Zend\ServiceManager\ServiceLocatorInterface $services) {
        parent::__construct('twitter');
        
        $em = $services->get('Doctrine\ORM\EntityManager');
        $this->setHydrator(new DoctrineHydrator($em, 'Scc\Entity\Twitter'))
                ->setObject(new \Scc\Entity\Twitter(''));

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Text'
        ));
        
        /*$this->add(array(
           'name' => 'node_id',
           'type' => 'Zend\Form\Element\Hidden'
        ));*/

        $this->add(array(
            'name' => 'html',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Html content'
            ),
            'attributes' => array(
                'required' => 'required'
            )
        ));
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification() {
        return array(
            'id' => array(
                'required' => false
            ),
            'html' => array(
                'required' => true
            )
        );
    }

}