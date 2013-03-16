<?php

namespace Scc\Service;

interface SiteServiceAware {

    public function setSiteService(\Scc\Service\Site $siteService);
}