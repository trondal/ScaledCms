<?php

namespace Application\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Zend\Mvc\Controller\AbstractActionController;

class ConsoleController extends AbstractActionController implements EntityManagerAware {

    /**
     * @var EntityManager
     */
    protected $em;

    public function setEntityManager(EntityManager $em) {
        $this->em = $em;
    }

    public function dropcreateAction() {
        $tool = new SchemaTool($this->em);
        $metaData = $this->em->getMetadataFactory()->getAllMetadata();

        $tool->dropSchema($metaData);
        $tool->createSchema($metaData);

        return 'Db dropped and created.' . PHP_EOL;
    }

    public function restartAction() {
        $this->dropcreateAction();

        $site1 = new \Application\Entity\Site('yellow');
        $site2 = new \Application\Entity\Site();
        $site3 = new \Application\Entity\Site('red');

        $page = new \Application\Entity\Page('index');
        $page1_1 = new \Application\Entity\Page('page1_1', 'page1_1');
        $page1_2 = new \Application\Entity\Page('page1_2', 'page1_2');
        $page2_1 = new \Application\Entity\Page('page2_1', 'page2_1');
        $page2_2 = new \Application\Entity\Page('page2_2', 'page2_2');

        $page1_1->setParent($page);
        $page1_2->setParent($page);
        $page2_1->setParent($page1_1);
        $page2_2->setParent($page1_1);

        $site2->addPage($page);
        $site2->addPage($page1_1);
        $site2->addPage($page1_2);
        $site2->addPage($page2_1);
        $site2->addPage($page2_2);

        $this->em->persist($site1);
        $this->em->persist($site2);
        $this->em->persist($site3);
        $this->em->persist($page);
        $this->em->persist($page1_1);
        $this->em->persist($page1_2);
        $this->em->persist($page2_1);
        $this->em->persist($page2_2);

        // Add components
        $twitter = new \Application\Entity\Twitter('<i>tweet:-)</i><br/>');
        $facebook = new \Application\Entity\Facebook('<b>Face!</b><br/>', '45644523');

        $node1 = new \Application\Entity\Node($twitter);
        $node2 = new \Application\Entity\Node($facebook);

        $page->addNode($node1);
        $page->addNode($node2);

        $this->em->persist($twitter);
        $this->em->persist($facebook);
        $this->em->persist($node1);
        $this->em->persist($node2);

        $this->em->flush();

        return 'Seeding done' . PHP_EOL;
    }

}