<?php

declare(strict_types=1);

namespace Statistics\Infrastructure\Factory;

use Interop\Container\ContainerInterface;
use Statistics\Domain\Service\AdvertiserServiceInterface;
use Statistics\Infrastructure\Service\AdvertiserService;
use Statistics\Infrastructure\Service\AuthServiceInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class AdvertiserServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): AdvertiserServiceInterface
    {
        /** @var AuthServiceInterface $authService */
        $authService = $container->get(AuthServiceInterface::class);

        $config = $container->get('Config')['adserver_synchronization'];
        $endpoint = $config['host'] . $config['url']['advertiser'];

        return new AdvertiserService($authService, $endpoint);
    }
}