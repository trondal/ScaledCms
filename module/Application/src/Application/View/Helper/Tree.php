<?php

namespace Application\View\Helper;

class Tree extends \Zend\Form\View\Helper\AbstractHelper {

    public function __invoke(\Application\Entity\Page $page) {
        return $this->displayTree($page);
    }

    private function displayTree(\Application\Entity\Page $parent, $level = 0) {
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