<?php

namespace Scc\Form\Fieldset;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Scc\Entity\Twitter;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TwitterFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct(ServiceLocatorInterface $services) {
        parent::__construct('twitter');
        
        $em = $services->get('Doctrine\ORM\EntityManager');
        $this->setHydrator(new DoctrineHydrator($em, 'Scc\Entity\Twitter'))
                ->setObject(new Twitter(''));

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Text'
        ));

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