<?php

namespace Application\Controller;

use Application\Service\PageService;

interface PageServiceAware {

    public function setPageService(PageService $pageService);
}