<?php

namespace Scc\Event;

use Scc\Entity\Node;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

/**
 * The NodeListener will initialize the component after the
 * node itself was loaded.
 */
class NodeListener implements EventSubscriber {

    public function getSubscribedEvents() {
	return array(
	    Events::postLoad,
	    Events::prePersist
	);
    }

    public function postLoad(LifecycleEventArgs $args) {
	$em = $args->getEntityManager();
	$node = $args->getEntity();

	if ($node instanceof Node) {
	    $className = 'Scc\Entity\\' . $node->getClassName();
	    $component = $em->getRepository($className)->findOneBy(array('node' => $node->getId()));
	    $node->setComponent($component);
	}
    }

    public function prePersist(LifecycleEventArgs $args) {
	$node = $args->getEntity();
	if ($node instanceOf Node) {
	    $em = $args->getEntityManager();
	    $component = $node->getComponent();
	    $em->persist($component);
	}
    }

}