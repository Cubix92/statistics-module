<?php

namespace Statistics\Application\Listener;

use Zend\Authentication\Adapter\Http as AdapterHttp;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Mvc\MvcEvent;

class AuthListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    protected $adapter;

    public function __construct(AdapterHttp $adapter)
    {
        $this->adapter = $adapter;
    }

    public function attach(EventManagerInterface $events, $priority = 1000)
    {
        $sharedManager = $events->getSharedManager();

        $this->listeners[] = $sharedManager->attach(
            AbstractRestfulController::class,
            MvcEvent::EVENT_DISPATCH,
            [$this, 'authorization'],
            $priority
        );
    }

    public function authorization(MvcEvent $event)
    {
        /** @var Response $response */
        $response = $event->getResponse();

        /** @var Request $request */
        $request = $event->getRequest();

        $this->adapter->setRequest($request)
            ->setResponse($response);

        $result = $this->adapter->authenticate();

        if (!$result->isValid()) {
            return $event->getResponse()->setContent('Invalid credentials!');
        }
    }
}
