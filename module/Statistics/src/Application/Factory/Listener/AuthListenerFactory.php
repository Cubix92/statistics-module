<?php

declare(strict_types=1);

namespace Statistics\Application\Factory\Listener;

use Interop\Container\ContainerInterface;
use Statistics\Application\Listener\AuthListener;
use Zend\Authentication\Adapter\Http\FileResolver;
use Zend\Authentication\Adapter\Http as AdapterHttp;
use Zend\ServiceManager\Factory\FactoryInterface;

class AuthListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): AuthListener
    {
        $config = $container->get('Config');

        $adapterConfig = $config['adserver_synchronization']['basic'];
        $path = $adapterConfig['basic_authentication_path'];

        $resolver = new FileResolver($path);
        $adapter = new AdapterHttp($adapterConfig);
        $adapter->setBasicResolver($resolver);

        return new AuthListener($adapter);
    }
}