<?php

namespace Scc\Controller;

use Scc\Service\PageService;

interface PageServiceAware {

    public function setPageService(PageService $pageService);
}