<?php

namespace Scc\Service;

interface UserServiceAware {

    public function setUserService(\Scc\Service\User $userService);
}