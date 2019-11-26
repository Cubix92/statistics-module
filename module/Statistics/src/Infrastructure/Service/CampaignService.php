<?php

declare(strict_types=1);

namespace Statistics\Infrastructure\Service;

use Statistics\Domain\Service\CampaignServiceInterface;

class CampaignService extends AbstractSynchronizeService implements CampaignServiceInterface
{
    public function fetchCampaigns(int $advertiserId): array
    {
        return $this->fetchData($this->endpoint . $advertiserId);
    }
}