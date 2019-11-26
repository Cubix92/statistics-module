<?php

declare(strict_types=1);

namespace StatisticsTest\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Statistics\Domain\Model\Banner;
use Statistics\Domain\Model\Campaign;
use Statistics\Domain\Model\Statistics;

class BannerTest extends TestCase
{
    private function createBanner(): Banner
    {
        $campaign = (new Campaign(123, 'example_campaign_name'));
        $banner = (new Banner(123, 'example_banner_name'))
            ->setCampaign($campaign);

        return $banner;
    }

    public function testBannerGetsPropertiesCorrectly()
    {
        $banner = $this->createBanner();

        $this->assertInstanceOf(Banner::class, $banner);
        $this->assertInstanceOf(Campaign::class, $banner->getCampaign());
        $this->assertIsString($banner->getId());
        $this->assertIsString($banner->getDescription());
        $this->assertIsInt($banner->getOrigin());
        $this->assertEquals('example_banner_name', $banner->getDescription());
        $this->assertEquals(123, $banner->getOrigin());
    }

    public function testBannerGetsStatisticsCorrectly()
    {
        $banner = $this->createBanner();
        $now = new \DateTime();

        $firstStatisticss = new Statistics($banner,clone($now)->modify('+ 1 DAY'), 100, 50, 500, 250);
        $secondStatisticss = new Statistics($banner, clone($now)->modify('+ 2 DAY'), 100, 50, 500, 250);
        $thirdStatisticss = new Statistics($banner, clone($now)->modify('+ 3 DAY'), 100, 50, 500, 250);

        $banner->addStatistics($firstStatisticss)
            ->addStatistics($firstStatisticss)
            ->addStatistics($secondStatisticss)
            ->addStatistics($thirdStatisticss);

        $this->assertCount(3, $banner->getStatistics());

        $banner->removeStatistics($firstStatisticss)
            ->removeStatistics($secondStatisticss)
            ->removeStatistics($thirdStatisticss);

        $this->assertCount(0, $banner->getStatistics());

        $statistics = new ArrayCollection([
            $firstStatisticss,
            $secondStatisticss
        ]);

        $banner->setStatistics($statistics);

        $this->assertCount(2, $banner->getStatistics());
    }
}