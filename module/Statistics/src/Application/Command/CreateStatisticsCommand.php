<?php

declare(strict_types=1);

namespace Statistics\Application\Command;

final class CreateStatisticsCommand
{
    public $date;

    public $origin;

    public $clicks;

    public $uniqueClicks;

    public $impressions;

    public $uniqueImpressions;

    public function __construct(
        \DateTime $date,
        int $origin,
        int $clicks,
        int $uniqueClicks,
        int $impressions,
        int $uniqueImpressions
    ) {
        $this->date = $date;
        $this->origin = $origin;
        $this->clicks = $clicks;
        $this->uniqueClicks = $uniqueClicks;
        $this->impressions = $impressions;
        $this->uniqueImpressions = $uniqueImpressions;
    }
}