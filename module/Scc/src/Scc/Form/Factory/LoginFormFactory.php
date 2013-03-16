<?php

namespace Scc\Form\Factory;

use Zend\Form\Factory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\NotEmpty;

class LoginFormFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
	$factory = new Factory();
	$form = $factory->createForm(
		array(
		    'name' => 'login',
		    'elements' => array(
			array(
			    'spec' => array(
				'name' => 'username',
				'type' => 'text',
				'options' => array(
				    'label' => 'Username',
				),
				'attributes' => array(
				    'autofocus' => true
				)
			    )
			),
			array(
			    'spec' => array(
				'name' => 'password',
				'type' => 'password',
				'options' => array(
				    'label' => 'Password'
				)
			    )
			),
			array(
			    'spec' => array(
				'name' => 'submit',
				'type' => 'submit',
				'attributes' => array(
				    'value' => 'Login'
				)
			    )
			)
		    ),
		    'input_filter' => array(
			'username' => array(
			    'validators' => array(
				array(
				    'name' => 'NotEmpty',
				    'break_chain_on_failure' => true,
				    'options' => array(
					'messages' => array(NotEmpty::IS_EMPTY => 'Username is required')
				    )
				)
			    )
			),
			'password' => array(
			    'validators' => array(
				array(
				    'name' => 'NotEmpty',
				    'break_chain_on_failure' => true,
				    'options' => array(
					'messages' => array(NotEmpty::IS_EMPTY => 'Password is required')
				    )
				)
			    )
			)
		    )
	));
	return $form;
    }

}