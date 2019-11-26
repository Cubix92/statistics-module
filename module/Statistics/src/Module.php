<?php

namespace Statistics;

use Statistics\Application\Listener\AuthListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $event)
    {
        $serviceManager = $event->getApplication()->getServiceManager();
        $eventManager = $event->getTarget()->getEventManager();

        $authListener = $serviceManager->get(AuthListener::class);
        $authListener->attach($eventManager);
    }
}
