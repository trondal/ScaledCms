<?php

namespace MessageBoard\Form;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use MessageBoard\Form\Fieldset\BoardFieldset;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorInterface;

class EditBoardForm extends Form {

    public function __construct(ServiceLocatorInterface $serviceLocator) {
	parent::__construct('new_board');

	$em = $serviceLocator->get('Doctrine\ORM\EntityManager');

	$hydrator = new DoctrineObject($em, 'MessageBoard\Entity\Board');
	$this->setHydrator($hydrator);

	$boardFieldset = new BoardFieldset($serviceLocator);
	$boardFieldset->setUseAsBaseFieldset(true);
	$this->add($boardFieldset);

	$this->add(array(
            'type' => 'Zend\Form\Element\Submit',
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Save'
            )
        ));
    }
}