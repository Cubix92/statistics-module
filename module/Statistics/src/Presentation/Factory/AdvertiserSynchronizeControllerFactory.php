<?php

declare(strict_types=1);

namespace Statistics\Presentation\Factory;

use Interop\Container\ContainerInterface;
use Statistics\Application\CommandBus;
use Statistics\Domain\Service\AdvertiserServiceInterface;
use Statistics\Presentation\Console\AdvertiserSynchronizeController;
use Zend\ServiceManager\Factory\FactoryInterface;

class AdvertiserSynchronizeControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): AdvertiserSynchronizeController
    {
        $commandBus = $container->get(CommandBus::class);
        $advertiserService = $container->get(AdvertiserServiceInterface::class);

        return new AdvertiserSynchronizeController($commandBus, $advertiserService);
    }
}