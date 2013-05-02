<?php

namespace Scc\Form\Fieldset;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class ContactFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct(\Zend\ServiceManager\ServiceLocatorInterface $services) {
        parent::__construct('twitter');
        
        $em = $services->get('Doctrine\ORM\EntityManager');
        $this->setHydrator(new DoctrineHydrator($em, 'Scc\Entity\Contact'))
                ->setObject(new \Scc\Entity\Contact('', '', ''));

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Text'
        ));

        $this->add(array(
            'name' => 'title',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Contact title'
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
            'title' => array(
                'required' => true
            )
        );
    }

}