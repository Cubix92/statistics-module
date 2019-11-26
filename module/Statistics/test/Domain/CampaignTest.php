<?php

declare(strict_types=1);

namespace StatisticsTest\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Statistics\Domain\Model\Advertiser;
use Statistics\Domain\Model\Banner;
use Statistics\Domain\Model\Campaign;

class CampaignTest extends TestCase
{
    private function createCampaign(): Campaign
    {
        $advertiser = new Advertiser(101, 'example_advertiser_name');
        $campaign = (new Campaign(123, 'example_campaign_name'));
        $campaign->setAdvertiser($advertiser);

        return $campaign;
    }

    public function testCampaignGetsPropertiesCorrectly()
    {
        $campaign = $this->createCampaign();

        $this->assertInstanceOf(Campaign::class, $campaign);
        $this->assertInstanceOf(Advertiser::class, $campaign->getAdvertiser());
        $this->assertIsString($campaign->getId());
        $this->assertIsString($campaign->getName());
        $this->assertIsInt($campaign->getOrigin());
        $this->assertEquals('example_campaign_name', $campaign->getName());
        $this->assertEquals(123, $campaign->getOrigin());
    }

    public function testCampaignsGetsBannersCorrectly()
    {
        $campaign = $this->createCampaign();

        $firstBanner = new Banner(101, 'example_first_banner_name');
        $secondBanner = new Banner(202, 'example_second_banner_name');
        $thirdBanner = new Banner(303, 'example_third_banner_name');

        $campaign->addBanner($firstBanner)
            ->addBanner($firstBanner)
            ->addBanner($secondBanner)
            ->addBanner($thirdBanner);

        $this->assertCount(3, $campaign->getBanners());

        $campaign->removeBanner($firstBanner)
            ->removeBanner($secondBanner)
            ->removeBanner($thirdBanner);

        $this->assertCount(0, $campaign->getBanners());

        $banners = new ArrayCollection([
            $firstBanner,
            $secondBanner
        ]);

        $campaign->setBanners($banners);

        $this->assertCount(2, $campaign->getBanners());
    }
}