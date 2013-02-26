<?php

namespace Application\Form\Fieldset;

use Application\Entity\Site;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SiteFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct(ServiceLocatorInterface $serviceLocator) {
	parent::__construct('site');

	$em = $serviceLocator->get('Doctrine\ORM\EntityManager');

	$hydrator = new DoctrineObject($em, 'Application\Entity\Site');
	$this->setHydrator($hydrator);
	$this->setObject(new Site('', ''));

	$this->add(array(
	    'name' => 'name',
	    'options' => array(
		'label' => 'Name'
	    )
	));

	$this->add(array(
	    'name' => 'slug',
	    'options' => array(
		'label' => 'Slug'
	    )
	));

	$pageFieldSet = new \Application\Form\Fieldset\PageFieldset($serviceLocator);
	$this->add(array(
	    'type' => 'Zend\Form\Element\Collection',
	    'name' => 'pages',
	    'options' => array(
		'label' => 'Pages',
		'target_element' => $pageFieldSet
	    )
	));
    }

    public function getInputFilterSpecification() {
	return array(
	    'name' => array(
		'required' => true
	    ),
	    'slug' => array(
		'required' => true
	    )
	);
    }

}