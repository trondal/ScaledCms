<?php

namespace MessageBoard\Form\Fieldset;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use MessageBoard\Entity\Board;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceManager;

class BoardFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct(ServiceManager $serviceManager) {
	parent::__construct('board');
	$em = $serviceManager->get('Doctrine\ORM\EntityManager');

	$this->setHydrator(new DoctrineHydrator($em, 'MessageBoard\Entity\Board'))
		->setObject(new Board(''));

	$this->add(array(
	    'name' => 'id',
	    'type' => 'Zend\Form\Element\Hidden'
	));

	$this->add(array(
	    'name' => 'title',
	    'type' => 'Zend\Form\Element\Text',
	    'options' => array(
		'label' => 'Title'
	    )
	));

	$messageFieldset = new MessageFieldset($serviceManager);
	$this->add(array(
	    'type' => 'Zend\Form\Element\Collection',
	    'name' => 'messages',
	    'options' => array(
		'label' => 'Messages',
		'count' => 2,
		'target_element' => $messageFieldset
	    )
	));
    }

    public function getInputFilterSpecification() {
	return array(
	    'id' => array(
		'required' => false,
		'validators' => array(
		    array(
			'name' => 'Int'
		    ),
		)
	    ),
	    'title' => array(
		'required' => true,
		'filters' => array(
		    array('name' => 'StripTags')
		),
		'validators' => array(
		    array(
			'name' => 'StringLength',
			'options' => array(
			    'encoding' => 'UTF-8',
			    'max' => 100,
			    'min' => 3,
			    'messages' => array(
				\Zend\Validator\StringLength::TOO_LONG => 'Title is too long',
				\Zend\Validator\StringLength::TOO_SHORT => 'Title is too short'
			    )
			)
		    )
		)
	    )
	);
    }

}