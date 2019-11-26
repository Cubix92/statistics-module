<?php

declare(strict_types=1);

namespace Statistics\Infrastructure\Factory;

use Interop\Container\ContainerInterface;
use Statistics\Infrastructure\Service\AuthServiceInterface;
use Statistics\Infrastructure\Service\BannerService;
use Zend\ServiceManager\Factory\FactoryInterface;

class BannerServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): BannerService
    {
        /** @var AuthServiceInterface $authService */
        $authService = $container->get(AuthServiceInterface::class);

        $config = $container->get('Config')['adserver_synchronization'];
        $endpoint = $config['host'] . $config['url']['banner'];

        return new BannerService($authService, $endpoint);
    }
}