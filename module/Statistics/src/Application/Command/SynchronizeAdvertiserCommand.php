<?php

declare(strict_types=1);

namespace Statistics\Application\Command;

final class SynchronizeAdvertiserCommand
{
    public $origin;

    public $name;

    public function __construct(int $origin, string $name)
    {
        $this->origin = $origin;
        $this->name = $name;
    }
}