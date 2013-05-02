<?php

namespace Scc\Form\Fieldset;

use Scc\Entity\Page;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PageFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct(ServiceLocatorInterface $serviceLocator) {
	parent::__construct('page');

	$em = $serviceLocator->get('Doctrine\ORM\EntityManager');

	$hydrator = new DoctrineObject($em, 'Scc\Entity\Page');
	$this->setHydrator($hydrator);
	$this->setObject(new Page('', ''));

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Page id'
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
	    'name' => 'slug',
            'type' => 'Zend\Form\Element\Text',
	    'options' => array(
		'label' => 'Slug'
	    )
	));
    }

    public function getInputFilterSpecification() {
	return array(
	    'title' => array(
		'required' => true
	    ),
	    'slug' => array(
		'required' => false
	    )
	);
    }

}