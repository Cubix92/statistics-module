<?php

declare(strict_types=1);

namespace Statistics\Application\Factory;

use Interop\Container\ContainerInterface;
use Statistics\Application\QueryBus;
use Zend\ServiceManager\Factory\FactoryInterface;

class QueryBusFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): QueryBus
    {
        return new QueryBus($container);
    }
}