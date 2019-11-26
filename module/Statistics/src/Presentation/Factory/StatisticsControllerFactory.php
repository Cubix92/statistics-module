<?php

declare(strict_types=1);

namespace Statistics\Presentation\Factory;

use Interop\Container\ContainerInterface;
use Statistics\Application\CommandBus;
use Statistics\Presentation\Api\StatisticsController;
use Zend\ServiceManager\Factory\FactoryInterface;

class StatisticsControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): StatisticsController
    {
        $commandBus = $container->get(CommandBus::class);

        return new StatisticsController($commandBus);
    }
}