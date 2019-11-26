<?php

declare(strict_types=1);

namespace Statistics\Domain\Repository;

use Doctrine\Common\Collections\Collection;
use Statistics\Domain\Model\Statistics;

interface StatisticsRepositoryInterface
{
    public function save(Statistics $statistics): void;

    public function findOneByBannerOriginAndDate(int $origin, \DateTime $date): ?Statistics;

    public function findForList(): Collection;
}