<?php

declare(strict_types=1);

namespace Statistics\Domain\Service;

interface CampaignServiceInterface
{
    public function fetchCampaigns(int $advertiserId): array;
}