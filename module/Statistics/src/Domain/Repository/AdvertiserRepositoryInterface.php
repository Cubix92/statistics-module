<?php

declare(strict_types=1);

namespace Statistics\Domain\Repository;

use Statistics\Domain\Model\Advertiser;

interface AdvertiserRepositoryInterface
{
    public function save(Advertiser $advertiser): void;

    public function findOneByOrigin(int $origin): ?Advertiser;

    public function findOneById(int $id): ?Advertiser;
}