<?php

declare(strict_types=1);

namespace Statistics\Application;

use Interop\Container\ContainerInterface;

class QueryBus
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function dispatch($query)
    {
        $handlerName = $this->getHandlerName($query);

        if (!$this->container->has($handlerName)) {
            throw new QueryHandlerNotFoundException(get_class($query));
        }

        $handler = $this->container->get($handlerName);
        return $handler->handle($query);
    }

    protected function getHandlerName($query): string
    {
        $queryClass = get_class($query);
        return substr($queryClass, 0, strrpos($queryClass, 'Query')) . 'Handler';
    }
}