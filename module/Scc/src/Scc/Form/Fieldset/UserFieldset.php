<?php

namespace Scc\Form\Fieldset;

use Scc\Entity\User;
use Scc\Form\Fieldset\SiteFieldset;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct(ServiceLocatorInterface $serviceLocator) {
	parent::__construct('user');

	$em = $serviceLocator->get('Doctrine\ORM\EntityManager');

	$hydrator = new DoctrineObject($em, 'Scc\Entity\User');
	$this->setHydrator($hydrator);
	$this->setObject(new User('', '', ''));

	$this->add(array(
	    'name' => 'name',
	    'options' => array(
		'label' => 'Name'
	    )
	));

	$this->add(array(
	    'name' => 'password',
	    'options' => array(
		'label' => 'Password'
	    )
	));

	$this->add(array(
	    'name' => 'email',
	    'options' => array(
		'label' => 'Email'
	    )
	));

	$siteFieldSet = new SiteFieldset($serviceLocator);
	$this->add(array(
	    'type' => 'Zend\Form\Element\Collection',
	    'name' => 'sites',
	    'options' => array(
		'label' => 'Sites',
		'target_element' => $siteFieldSet
	    )
	));
    }

    public function getInputFilterSpecification() {
	return array(
	    'name' => array(
		'required' => true
	    ),
	    'password' => array(
		'required' => true
	    ),
	    'email' => array(
		'required' => true
	    )
	);
    }

}