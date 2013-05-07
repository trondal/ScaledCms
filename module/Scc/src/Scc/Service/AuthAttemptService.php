<?php
namespace Scc\Service;

use Doctrine\ORM\EntityManager;
use Scc\Controller\EntityManagerAware;
use Scc\Entity\AuthAttempt;
use Scc\Entity\User;

class AuthAttemptService implements EntityManagerAware, ConfigurationAware {
    /**
     * @var EntityManager 
     */
    protected $em;
    
    protected $config;

    public function setEntityManager(EntityManager $em) {
        $this->em = $em;
    }
    
    public function setConfiguration(array $configuration) {
        $this->config = $configuration;
    }
    
    /**
     * @param User $user
     * @return array
     */
    public function getLastSuccessfulAttempt(User $user) {
        $timeFormat = $this->config['app']['time']['format'];
        $result = $this->em->createQuery('
            SELECT u.id, u.username, u.email, u.firstName, u.surname, l.time, l.ip 
            FROM Scc\Entity\AuthAttempt l JOIN l.user u WHERE l.id =
            (SELECT MAX(e.id) FROM \Scc\Entity\AuthAttempt e 
            WHERE e.user = :user AND e.status = 1)')
            ->setParameter('user', $user->getId())
            ->getArrayResult();
        return array('id' => $result[0]['id'],
            'username' => $result[0]['username'],
            'email' => $result[0]['email'],
            'firstname' => $result[0]['firstName'],
            'surname' => $result[0]['surname'],
            'time' => $result[0]['time']->format($timeFormat),
            'ip' => $result[0]['ip']
        );
     }
     
     public function save(AuthAttempt $authAttempt) {
         $this->em->persist($authAttempt);
         $this->em->flush();
     }
}