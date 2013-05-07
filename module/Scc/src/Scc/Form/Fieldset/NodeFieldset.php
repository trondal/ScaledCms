<?php

namespace Scc\Form\Fieldset;

use Scc\Controller\ComponentHydrator;
use Scc\Entity\Node;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceManager;

class NodeFieldset extends Fieldset implements InputFilterProviderInterface {

    protected $services;

    public function __construct(ServiceManager $services) {
        parent::__construct('node');

        $this->services = $services;
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
    }

    public function getInputFilterSpecification() {
        return array();
    }

    private $count = 0;

    public function setObject($object) {
        $name = $object->getClassName();

        if ($name) {
            if (in_array($name, array('Scc\Entity\Contact'))) {             
                $fieldsetClassName = $this->getFieldsetClassName($name);
                $fieldsetName = $this->getFieldsetName($name);

                $fieldset = new $fieldsetClassName($this->services);
                $this->add(array(
                    'type' => 'Zend\Form\Element\Collection',
                    'name' => $fieldsetName,
                    'options' => array(
                        'should_create_template' => false,
                        'template_placeholder' => '__placeholder__',
                        'allow_add' => false,
                        'label' => $fieldsetName,
                        'target_element' => $fieldset
                    )
                ));
            }
        }
        parent::setObject($object);
    }

    private function getFieldsetClassName($ns) {
        return $this->getNsPrefix($ns) . '\Form\Fieldset\\' . $this->getNsSuffix($ns) . 'Fieldset';
    }

    private function getFieldsetName($ns) {
        $this->count++;
        return lcfirst($this->getNsSuffix($ns)) . 's';
    }

    private function getNsPrefix($ns) {
        $result = explode('\\', $ns);
        return $result[0];
    }

    private function getNsSuffix($ns) {
        $result = explode('\\', $ns);
        return $result[count($result) - 1];
    }

    public function populateValues($data) {
        parent::populateValues($data);
    }
    
}