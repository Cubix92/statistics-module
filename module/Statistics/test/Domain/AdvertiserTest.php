<?php

declare(strict_types=1);

namespace StatisticsTest\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Statistics\Domain\Model\Advertiser;
use Statistics\Domain\Model\Campaign;

class AdvertiserTest extends TestCase
{
    private function createAdvertiser(): Advertiser
    {
        return new Advertiser(123, 'example_advertiser_name');
    }

    public function testAdvertiserGetsPropertiesCorrectly()
    {
        $advertiser = $this->createAdvertiser();

        $this->assertInstanceOf(Advertiser::class, $advertiser);
        $this->assertIsString($advertiser->getId());
        $this->assertIsString($advertiser->getName());
        $this->assertIsInt($advertiser->getOrigin());
        $this->assertEquals('example_advertiser_name', $advertiser->getName());
        $this->assertEquals(123, $advertiser->getOrigin());
    }

    public function testAdvertiserGetsCampaignsCorrectly()
    {
        $advertiser = $this->createAdvertiser();

        $firstCampaign = new Campaign(101, 'example_first_campaign_name');
        $secondCampaign = new Campaign(202, 'example_second_campaign_name');
        $thirdCampaign = new Campaign(303, 'example_third_campaign_name');

        $advertiser->addCampaign($firstCampaign)
            ->addCampaign($firstCampaign)
            ->addCampaign($secondCampaign)
            ->addCampaign($thirdCampaign);

        $this->assertCount(3, $advertiser->getCampaigns());

        $advertiser->removeCampaign($firstCampaign)
            ->removeCampaign($secondCampaign)
            ->removeCampaign($thirdCampaign);

        $this->assertCount(0, $advertiser->getCampaigns());

        $campaigns = new ArrayCollection([
            $firstCampaign,
            $secondCampaign
        ]);

        $advertiser->setCampaigns($campaigns);

        $this->assertCount(2, $advertiser->getCampaigns());
    }
}