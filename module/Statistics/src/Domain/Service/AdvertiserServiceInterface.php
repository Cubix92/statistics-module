<?php

declare(strict_types=1);

namespace Statistics\Domain\Service;

interface AdvertiserServiceInterface
{
    public function fetchAdvertisers(): array;
}