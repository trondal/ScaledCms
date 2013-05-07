<?php

namespace Scc\Validator;

class TwitterValidator extends \Zend\Validator\AbstractValidator {
    
    const HTML = 'Missing html value';

    protected $messageTemplates = array(
        self::HTML => "Missing html value"
    );
    
    public function isValid($data) {
        if (!isset($data['html'])) {
            $this->error(self::HTML);
            return false;
        }
        return true;
    }
    
}