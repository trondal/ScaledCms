<?php

namespace Scc\Form\Fieldset;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Scc\Entity\Panel;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PanelFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct(ServiceLocatorInterface $services) {
        parent::__construct('panel');
        
        $em = $services->get('Doctrine\ORM\EntityManager');
        $this->setHydrator(new DoctrineHydrator($em, 'Scc\Entity\Panel'))
                ->setObject(new Panel());

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Text'
        ));

    }

    /**
     * @return array
     */
    public function getInputFilterSpecification() {
        return array(
            'id' => array(
                'required' => false
            )
        );
    }

}