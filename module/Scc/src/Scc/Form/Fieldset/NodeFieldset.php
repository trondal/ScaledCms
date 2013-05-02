<?php

namespace Scc\Form\Fieldset;

use Scc\Controller\ComponentHydrator;
use Scc\Entity\Node;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceManager;

class NodeFieldset extends Fieldset implements InputFilterProviderInterface {

    public function __construct(ServiceManager $services) {
        parent::__construct('node');

        $em = $services->get('Doctrine\ORM\EntityManager');

        $this->setHydrator(new ComponentHydrator($em, 'Scc\Entity\Node'))
                ->setObject(new Node());

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Node Id'
            )
        ));

        $this->add(array(
            'name' => 'className',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Class Name'
            )
        ));

        $contactFieldset = new ContactFieldset($services);
        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'contacts',
            'options' => array(
                'should_create_template' => false,
                'allow_add' => false,
                'label' => 'contacts',
                'target_element' => $contactFieldset
            )
        ));

        /* $twitterFieldset = new TwitterFieldset($services);
          $this->add(array(
          'type' => 'Zend\Form\Element\Collection',
          'name' => 'twitters',
          'options' => array(
          'should_create_template' => false,
          'allow_add' => false,
          'label' => 'twitters',
          'target_element' => $twitterFieldset
          )
          )); */
    }

    public function getInputFilterSpecification() {
        return array();
    }

}