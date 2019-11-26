<?php

declare(strict_types=1);

namespace Statistics\Application\Command;

final class SynchronizeCampaignCommand
{
    public $origin;

    public $name;

    public $advertiserId;

    public function __construct(int $origin, string $name, int $advertiserId)
    {
        $this->origin = $origin;
        $this->name = $name;
        $this->advertiserId = $advertiserId;
    }
}