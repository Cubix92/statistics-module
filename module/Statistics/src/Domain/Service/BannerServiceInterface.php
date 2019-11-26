<?php

declare(strict_types=1);

namespace Statistics\Domain\Service;

interface BannerServiceInterface
{
    public function fetchBanners(int $campaignId): array;
}