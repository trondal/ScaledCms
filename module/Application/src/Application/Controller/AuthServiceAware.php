<?php

namespace Application\Controller;

use Zend\Authentication\AuthenticationService;

interface AuthServiceAware {

    public function setAuthService(AuthenticationService $authService);

}