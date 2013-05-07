<?php

namespace Scc\Service;

use Scc\Service\AuthAttemptService;

interface AuthAttemptServiceAware {
    
    public function setAuthAttemptService(AuthAttemptService $authAttemptService);
}