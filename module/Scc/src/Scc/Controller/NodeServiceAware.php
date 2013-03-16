<?php

namespace Scc\Controller;

use Scc\Service\NodeService;

interface NodeServiceAware {

    public function setNodeService(NodeService $nodeService);

}