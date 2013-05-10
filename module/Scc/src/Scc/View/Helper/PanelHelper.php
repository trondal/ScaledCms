<?php

namespace Scc\View\Helper;

use Scc\Entity\Node;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class PanelHelper extends AbstractHelper {

    public function __invoke(Node $node) {
	$model = new ViewModel(array('node' => $node));
	$model->setTemplate('scc/panel/index');
	return $this->getView()->render($model);
    }

}