<?php
namespace MessageBoard\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class NewMessageForm extends Form {

    /**
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    public function __construct($serviceLocator) {

        parent::__construct('messageboard_newmessage');

        $this->em = $serviceLocator->get('Doctrine\ORM\EntityManager');

        $this->setAttribute('method', 'post')
            ->setHydrator(new DoctrineHydrator($this->em, 'MessageBoard\Entity\Board'))
            ->setInputFilter(new InputFilter);

        $boardFieldset = new \MessageBoard\Form\Fieldset\BoardFieldset($serviceLocator);
        $boardFieldset->setUseAsBaseFieldset(true);

        $this->add($boardFieldset);

        $this->add(array(
            'type' => 'Zend\Form\Element\Submit',
            'name' => 'submit',
            'attributes' => array(
                'value' => 'save'
            )
        ));
    }
}
