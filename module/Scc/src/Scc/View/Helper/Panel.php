<?php

namespace Scc\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class Panel extends AbstractHelper {

    public function handle(\Scc\Entity\Contact $contact = null) {
	$model = new ViewModel();
	$model->setTemplate('scc/panel/start');
	return $this->getView()->render($model);
    }

    public function renderEnd() {
	$model = new ViewModel();
	$model->setTemplate('scc/panel/end');
	return $this->getView()->render($model);
    }

}