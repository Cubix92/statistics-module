<?php

declare(strict_types=1);

namespace StatisticsTest\Domain;

use PHPUnit\Framework\TestCase;
use Statistics\Domain\Model\Banner;
use Statistics\Domain\Model\Statistics;

class StatisticsTest extends TestCase
{
    const BANNER_ID = 123;
    const BANNER_CLICKS = 500;

    public static function createStatisticsFake(): Statistics
    {
        $now = new \DateTime();
        $banner = (new Banner(self::BANNER_ID, 'example_banner_name'));

        $statistics = (new Statistics($banner, $now, self::BANNER_CLICKS, 300, 2000, 1000));

        return $statistics;
    }

    public function testStatisticsGetsPropertiesCorrectly()
    {
        $statistics = self::createStatisticsFake();

        $this->assertInstanceOf(Statistics::class, $statistics);
        $this->assertInstanceOf(Banner::class, $statistics->getBanner());
        $this->assertInstanceOf(\DateTime::class, $statistics->getDate());
        $this->assertIsInt($statistics->getClicks());
        $this->assertIsInt($statistics->getUniqueClicks());
        $this->assertIsInt($statistics->getImpressions());
        $this->assertIsInt($statistics->getUniqueImpressions());
        $this->assertEquals(self::BANNER_CLICKS, $statistics->getClicks());
        $this->assertEquals(300, $statistics->getUniqueClicks());
        $this->assertEquals(2000, $statistics->getImpressions());
        $this->assertEquals(1000, $statistics->getUniqueImpressions());
    }
}