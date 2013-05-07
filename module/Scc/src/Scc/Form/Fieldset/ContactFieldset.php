<?php

namespace Scc\Form\Fieldset;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Scc\Entity\Contact;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ContactFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct(ServiceLocatorInterface $services) {
        parent::__construct('contact');
        
        $em = $services->get('Doctrine\ORM\EntityManager');
        $this->setHydrator(new DoctrineHydrator($em, 'Scc\Entity\Contact'))
                ->setObject(new Contact('', '', ''));

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
                'required' => true,
                'validators' => array(
                    
                )
            )
        );
    }

}