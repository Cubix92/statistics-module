<?php

declare(strict_types=1);

namespace Statistics\Domain\Repository;

use Statistics\Domain\Model\Banner;

interface BannerRepositoryInterface
{
    public function save(Banner $banner): void;

    public function findOneByOrigin(int $origin): ?Banner;
}