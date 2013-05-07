<?php

namespace Scc\Controller;

use Swagger\Swagger;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class DocsController extends AbstractActionController {

    /**
     *
     * @var Swagger
     */
    protected $swagger;

    public function indexAction() {
        $resourceName = $this->params()->fromRoute('resource');
        $swagger = Swagger::discover('/Users/trondal/Sites/ScaledCms/module/Scc/src/Scc/Model');

        $result = $swagger->registry['/pet'];
        $jsonModel = new JsonModel(
                array('model' => $result)
        );

        return $jsonModel;
    }

}