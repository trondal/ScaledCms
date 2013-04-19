<?php

namespace Scc\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class Panel extends AbstractHelper {

    public function handle(\Scc\Entity\Panel $panel = null) {
	$model = new ViewModel();
	$model->setTemplate('scc/panel/index');
	return $this->getView()->render($model);
    }

}