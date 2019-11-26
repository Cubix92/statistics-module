<?php

declare(strict_types=1);

namespace Statistics\Application\Command;

use Statistics\Domain\Exception\StatisticsDuplicateException;
use Statistics\Domain\Model\Statistics;
use Statistics\Domain\Repository\BannerRepositoryInterface;
use Statistics\Domain\Repository\StatisticsRepositoryInterface;

final class CreateStatisticsHandler
{
    private $statisticsRepository;

    private $bannerRepository;

    public function __construct(StatisticsRepositoryInterface $statisticsRepository, BannerRepositoryInterface $bannerRepository)
    {
        $this->statisticsRepository = $statisticsRepository;
        $this->bannerRepository = $bannerRepository;
    }

    public function handle(CreateStatisticsCommand $command) : void
    {
        if ($this->statisticsRepository->findOneByBannerOriginAndDate($command->origin, $command->date)) {
            throw new StatisticsDuplicateException(
                "On {$command->date->format('Y-m-d')} statistics were added and cannot be overwritten."
            );
        }

        $banner = $this->bannerRepository->findOneByOrigin($command->origin);

        if (!$banner) {
            throw new BannerNotFoundException(
                "Banner with id {$command->origin} was not found."
            );
        }

        $statistics = new Statistics(
            $banner,
            $command->date,
            $command->clicks,
            $command->uniqueClicks,
            $command->impressions,
            $command->uniqueImpressions
        );

        $this->statisticsRepository->save($statistics);
    }
}