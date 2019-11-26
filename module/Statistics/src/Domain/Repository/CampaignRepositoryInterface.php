<?php

declare(strict_types=1);

namespace Statistics\Domain\Repository;

use Statistics\Domain\Model\Campaign;

interface CampaignRepositoryInterface
{
    public function save(Campaign $campaign): void;

    public function findOneByOrigin(int $origin): ?Campaign;

    public function findOneById(int $id): ?Campaign;
}