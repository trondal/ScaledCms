<?php

namespace Application\Controller;

use Application\Form\CreatePage;
use Application\Service\Site;
use Application\Service\SiteServiceAware;
use Application\Service\User;
use Application\Service\UserServiceAware;
use Doctrine\Common\Util\Debug;
use Zend\Mvc\Controller\AbstractActionController;

class PageController extends AbstractActionController implements UserServiceAware, SiteServiceAware {

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var SiteService
     */
    protected $siteService;

    public function setUserService(User $userService) {
        $this->userService = $userService;
    }

    public function setSiteService(Site $siteService) {
        $this->siteService = $siteService;
    }

    public function indexAction() {
        $form = new CreatePage($this->getServiceLocator());

        $user = $this->userService->findOneByName('Alice');
        $form->bind($user);

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                echo '<pre>';
                Debug::dump($user);
                exit;
            }
        }

        return array(
            'form' => $form
        );
    }

}