<?php

namespace MessageBoard\Form\Fieldset;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use MessageBoard\Entity\Message;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceManager;

class MessageFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct(ServiceManager $serviceManager) {
	parent::__construct('message');
	$em = $serviceManager->get('Doctrine\ORM\EntityManager');

	$this->setHydrator(new DoctrineHydrator($em, 'MessageBoard\Entity\Message'))
		->setObject(new Message('', '', ''));

	$this->setLabel('Message');

	$this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ));

	$this->add(array(
	    'name' => 'sender',
	    'type' => 'Zend\Form\Element\Text',
	    'options' => array(
		'label' => 'Sender'
	    )
	));

	$this->add(array(
	    'name' => 'title',
	    'type' => 'Zend\Form\Element\Text',
	    'options' => array(
		'label' => 'Title'
	    )
	));

	$this->add(array(
	    'name' => 'content',
	    'type' => 'Zend\Form\Element\Text',
	    'options' => array(
		'label' => 'Content'
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
	    'sender' => array(
		'required' => true,
		'filters' => array(
		    array('name' => 'StripTags')
		),
		'validators' => array(
		    array(
			'name' => 'StringLength',
			'options' => array(
			    'encoding' => 'UTF-8',
			    'max' => 128,
			    'min' => 1,
			    'messages' => array(
				\Zend\Validator\StringLength::TOO_LONG => 'Sender is too long',
				\Zend\Validator\StringLength::TOO_SHORT => 'Sender is too short'
			    )
			)
		    )
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
			    'max' => 128,
			    'min' => 1,
			    'messages' => array(
				\Zend\Validator\StringLength::TOO_LONG => 'Title is too long',
				\Zend\Validator\StringLength::TOO_SHORT => 'Title is too short'
			    )
			)
		    )
		)
	    ),
	    'content' => array(
		'required' => true,
		'filters' => array(
		    array('name' => 'StripTags')
		),
		'validators' => array(
		    array(
			'name' => 'StringLength',
			'options' => array(
			    'encoding' => 'UTF-8',
			    'max' => 1000,
			    'min' => 1,
			    'messages' => array(
				\Zend\Validator\StringLength::TOO_LONG => 'Content is too long',
				\Zend\Validator\StringLength::TOO_SHORT => 'Content is too short'
			    )
			)
		    )
		)
	    )
	);
    }

}