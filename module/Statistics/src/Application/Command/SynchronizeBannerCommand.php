<?php

declare(strict_types=1);

namespace Statistics\Application\Command;

final class SynchronizeBannerCommand
{
    public $origin;

    public $description;

    public $campaignId;

    public function __construct(int $origin, string $description, int $campaignId)
    {
        $this->origin = $origin;
        $this->description = $description;
        $this->campaignId = $campaignId;
    }
}