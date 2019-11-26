<?php

declare(strict_types=1);

namespace Statistics\Application\Query;

final class ListStatisticsQuery
{
    public $dateFrom;

    public $dateTo;

    public $filters;

    public function __construct(\DateTime $dateFrom, \DateTime $dateTo, array $filters = [])
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->filters = $filters;
    }
}