<?php

namespace Application\Service;

interface UserServiceAware {

    public function setUserService(\Application\Service\User $userService);

}