<?php

namespace Application\Form;

use Application\Form\Fieldset\UserFieldset;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\ServiceManager\ServiceLocatorInterface;

class CreatePage extends Form {

    public function __construct(ServiceLocatorInterface $serviceLocator) {
        parent::__construct('create_page');

        $em = $serviceLocator->get('Doctrine\ORM\EntityManager');

        $this->setAttribute('method', 'post');
        $this->setHydrator(new DoctrineObject($em, 'Application\Entity\User'));

        $fieldSet = new UserFieldset($serviceLocator);
        $fieldSet->setUseAsBaseFieldset(true);
        $this->add($fieldSet);

        $this->add(array(
           'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Ok'
            )
        ));
    }

}