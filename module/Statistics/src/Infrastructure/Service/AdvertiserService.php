<?php

declare(strict_types=1);

namespace Statistics\Infrastructure\Service;

use Statistics\Domain\Service\AdvertiserServiceInterface;

class AdvertiserService extends AbstractSynchronizeService implements AdvertiserServiceInterface
{
    const ADMIN_ID = 1;

    public function fetchAdvertisers(): array
    {
        return $this->fetchData($this->endpoint . self::ADMIN_ID);
    }
}