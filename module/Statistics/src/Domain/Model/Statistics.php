<?php

declare(strict_types=1);

namespace Statistics\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass="\Statistics\Infrastructure\Repository\StatisticsRepository")
 * @ORM\Table(name="ad_statistic")
 */
class Statistics
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Banner", inversedBy="statistics")
     */
    protected $banner;

    /**
     * @ORM\Column(type="date", name="date")
     */
    protected $date;

    /**
     * @ORM\Column(type="integer", name="clicks")
     */
    protected $clicks;

    /**
     * @ORM\Column(type="integer", name="unique_clicks")
     */
    protected $uniqueClicks;

    /**
     * @ORM\Column(type="integer", name="impressions")
     */
    protected $impressions;

    /**
     * @ORM\Column(type="integer", name="unique_impressions")
     */
    protected $uniqueImpressions;

    public function __construct(
        Banner $banner,
        \DateTime $date,
        int $clicks,
        int $uniqueClicks,
        int $impressions,
        int $uniqueImpressions
    ) {
        $this->id = Uuid::uuid4()->toString();
        $this->banner = $banner;
        $this->date = $date;
        $this->clicks = $clicks;
        $this->uniqueClicks = $uniqueClicks;
        $this->impressions = $impressions;
        $this->uniqueImpressions = $uniqueImpressions;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBanner(): Banner
    {
        return $this->banner;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function getClicks(): int
    {
        return $this->clicks;
    }

    public function getUniqueClicks(): int
    {
        return $this->uniqueClicks;
    }

    public function getImpressions(): int
    {
        return $this->impressions;
    }

    public function getUniqueImpressions(): int
    {
        return $this->uniqueImpressions;
    }
}