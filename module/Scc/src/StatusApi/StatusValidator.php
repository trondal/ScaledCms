<?php

namespace StatusApi;

class StatusValidator {

    public function isValid(StatusInterface $status) {
        $user = $status->getUser();
        if (!is_string($user)) {
            return false;
        }
        $user = trim($user);
        if (empty($user)) {
            return false;
        }
        
        return true;
    }

    public function validateStatus(StatusInterface $status) {
        $text = $status->getText();
        if (!is_string($text)) {
            return false;
        }
        $text = trim($text);
        if (empty($text)) {
            return false;
        }
        return true;
    }

}