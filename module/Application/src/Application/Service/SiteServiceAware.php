<?php

namespace Application\Service;

interface SiteServiceAware {

    public function setSiteService(\Application\Service\Site $siteService);

}