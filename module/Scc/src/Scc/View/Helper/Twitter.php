<?php

namespace Scc\View\Helper;

use Scc\Entity\Twitter as TwitterEntity;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class Twitter extends AbstractHelper {

    public function handle(TwitterEntity $twitter) {
	$model = new ViewModel(array('component' => $twitter));
	$model->setTemplate('scc/twitter/index');
	return $this->getView()->render($model);
    }

}