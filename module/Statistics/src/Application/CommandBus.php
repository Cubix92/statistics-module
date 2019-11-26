<?php

declare(strict_types=1);

namespace Statistics\Application;

use Interop\Container\ContainerInterface;

class CommandBus
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function execute($command) : void
    {
        $handlerName = $this->getHandlerName($command);

        if (!$this->container->has($handlerName)) {
            throw new CommandHandlerNotFoundException(get_class($command));
        }

        $handler = $this->container->get($handlerName);
        $handler->handle($command);
    }

    private function getHandlerName($command) : string
    {
        $commandClass = get_class($command);
        return substr($commandClass, 0, strrpos($commandClass, 'Command')) . 'Handler';
    }
}