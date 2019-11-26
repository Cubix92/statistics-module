<?php

declare(strict_types=1);

namespace Statistics\Application\Factory;

use Interop\Container\ContainerInterface;
use Statistics\Application\CommandBus;
use Zend\ServiceManager\Factory\FactoryInterface;

class CommandBusFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): CommandBus
    {
        return new CommandBus($container);
    }
}