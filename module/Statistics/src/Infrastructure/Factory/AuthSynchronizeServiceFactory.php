<?php

declare(strict_types=1);

namespace Statistics\Infrastructure\Factory;

use Interop\Container\ContainerInterface;
use Statistics\Infrastructure\Service\AuthService;
use Statistics\Infrastructure\Service\AuthServiceInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class AuthSynchronizeServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): AuthServiceInterface
    {
        $config = $container->get('Config')['adserver_synchronization'];

        $endpoint = $config['host'] . $config['auth']['endpoint'];
        $username = $config['auth']['username'];
        $password = $config['auth']['password'];

        return new AuthService($endpoint, $username, $password);
    }
}