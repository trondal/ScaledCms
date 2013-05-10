<?php

namespace Scc\View\Helper;

use Scc\Entity\Node;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class TwitterHelper extends AbstractHelper {

    public function __invoke(Node $node) {
	$model = new ViewModel(array('component' => $node->getComponent()));
	$model->setTemplate('scc/twitter/index');
	return $this->getView()->render($model);
    }

}