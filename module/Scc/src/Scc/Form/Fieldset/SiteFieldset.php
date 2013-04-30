<?php

namespace Scc\Form\Fieldset;

use Scc\Controller\ComponentHydrator;
use Scc\Entity\Site;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SiteFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct(ServiceLocatorInterface $serviceLocator) {
	parent::__construct('site');

	$em = $serviceLocator->get('Doctrine\ORM\EntityManager');

	$hydrator = new ComponentHydrator($em, 'Scc\Entity\Site');
	$this->setHydrator($hydrator);
	$this->setObject(new Site('', ''));

	$this->add(array(
	    'name' => 'title',
	    'options' => array(
		'label' => 'Title'
	    )
	));

	$pageFieldSet = new \Scc\Form\Fieldset\PageFieldset($serviceLocator);
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
	    )
        );
    }

}