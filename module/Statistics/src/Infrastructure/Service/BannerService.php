<?php

declare(strict_types=1);

namespace Statistics\Infrastructure\Service;

use Statistics\Domain\Service\BannerServiceInterface;

class BannerService extends AbstractSynchronizeService implements BannerServiceInterface
{
    public function fetchBanners(int $campaignId): array
    {
        $response = current($this->fetchData($this->endpoint . $campaignId));

        if ($response === 'error') {
            return [];
        }

        return $response;
    }
}