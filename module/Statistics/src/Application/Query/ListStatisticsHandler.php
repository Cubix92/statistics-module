<?php

declare(strict_types=1);

namespace Statistics\Application\Query;

use Statistics\Domain\Model\Statistics;
use Statistics\Domain\Repository\StatisticsRepositoryInterface;

class ListStatisticsHandler
{
    private $statisticsRepository;

    public function __construct(StatisticsRepositoryInterface $statisticsRepository)
    {
        $this->statisticsRepository = $statisticsRepository;
    }

    public function handle(ListStatisticsQuery $query)
    {
        $statisticsCollection = $this->statisticsRepository->findForList(
            $query->dateFrom,
            $query->dateTo,
            $query->filters
        );

        return array_map(function(Statistics $statistics) {
            return [
                'date' => $statistics->getDate()->format('Y-m-d'),
                'clicks' => $statistics->getClicks(),
                'unique_clicks' => $statistics->getUniqueClicks(),
                'impressions' => $statistics->getUniqueImpressions(),
                'unique_impressions' => $statistics->getImpressions()
            ];
        }, $statisticsCollection->toArray());
    }
}