<?php

namespace Scc\View\Helper;

use Scc\Entity\Page;
use Zend\Form\View\Helper\AbstractHelper;

class TreeHelper extends AbstractHelper {

    public function __invoke(Page $page) {
	return $this->displayTree($page);
    }

    private function displayTree(Page $parent, $level = 0) {
	$html = '';
	if ($level === 0) {
	    $html .= '<ul>';
	    $html .= '<li>' . $parent->getId() . ' ' . $parent->getTitle() . '</li>';
	}

	$html .= '<ul>';
	$children = $parent->getChildren();

	// display each child
	foreach ($children as $child) {
	    $html .= '<li>' . $child->getId() . ' ' . $child->getTitle() . '</li>';
	    $html .= $this->displayTree($child, $level + 1);
	}

	$html .= '</ul>';

	if ($level === 0) {
	    $html .= '</ul>';
	}
	return $html;
    }

}