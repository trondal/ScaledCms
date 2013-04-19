<?php

namespace MessageBoard\Form;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class NewBoardForm extends \Zend\Form\Form {

    public function __construct(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator){
	parent::__construct('messageboard_newboard');

	$em = $serviceLocator->get('Doctrine\ORM\EntityManager');

	$this->setHydrator(new DoctrineHydrator($em, 'MessageBoard\Entity\Board'));

	$boardFieldset = new Fieldset\BoardFieldset($serviceLocator);
	$boardFieldset->setUseAsBaseFieldset(true);
	$this->add($boardFieldset);

	$this->add(array(
            'type' => 'Zend\Form\Element\Submit',
            'name' => 'submit',
            'attributes' => array(
                'value' => 'save'
            )
        ));
    }
}