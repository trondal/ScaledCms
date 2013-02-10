<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FacebookController extends AbstractActionController {

    public function indexAction() {
        $component = $this->params('component');

        return new ViewModel(array('component' => $component));
    }

}