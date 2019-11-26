<?php

declare(strict_types=1);

namespace StatisticsTest\Application\Command;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Statistics\Application\Command\SynchronizeCampaignCommand;
use Statistics\Application\Command\SynchronizeCampaignHandler;
use Statistics\Domain\Model\Advertiser;
use Statistics\Domain\Model\Campaign;
use Statistics\Domain\Repository\AdvertiserRepositoryInterface;
use Statistics\Domain\Repository\CampaignRepositoryInterface;

class CampaignCommandTest extends TestCase
{
    const CAMPAIGN_ID = 101;
    const CAMPAIGN_NAME = 'example_campaign_name';
    const ADVERTISER_ID = 202;

    /** @var AdvertiserRepositoryInterface $advertiserRepository */
    private $advertiserRepository;

    /** @var CampaignRepositoryInterface $campaignRepository */
    private $campaignRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->advertiserRepository = $this->prophesize(AdvertiserRepositoryInterface::class);
        $this->campaignRepository = $this->prophesize(CampaignRepositoryInterface::class);
    }

    public function testCreateInSynchronizeCampaignCommandExecutesProperly()
    {
        /** @var Advertiser $advertiser */
        $advertiser = $this->prophesize(Advertiser::class);

        $this->advertiserRepository->findOneByOrigin(Argument::is(self::ADVERTISER_ID))
            ->willReturn($advertiser->reveal())
            ->shouldBeCalled();

        $this->campaignRepository->findOneByOrigin(Argument::type('integer'))
            ->willReturn(null)
            ->shouldBeCalled();

        $this->campaignRepository->save(Argument::type(Campaign::class))
            ->shouldBeCalled();

        $synchronizeCampaignCommand = new SynchronizeCampaignCommand(
            self::CAMPAIGN_ID,
            self::CAMPAIGN_NAME,
            self::ADVERTISER_ID
        );

        $synchronizeCampaignHandler = new SynchronizeCampaignHandler(
            $this->campaignRepository->reveal(),
            $this->advertiserRepository->reveal()
        );

        $synchronizeCampaignHandler->handle($synchronizeCampaignCommand);
    }

    public function testUpdateInSynchronizeCampaignCommandExecutesProperly()
    {
        /** @var Campaign $campaign */
        $campaign = $this->prophesize(Campaign::class);

        $campaign->setAdvertiser(Argument::type(Advertiser::class))
            ->shouldBeCalled();

        $campaign->setName(Argument::type('string'))
            ->shouldBeCalled();

        /** @var Advertiser $advertiser */
        $advertiser = $this->prophesize(Advertiser::class);

        $this->advertiserRepository->findOneByOrigin(Argument::is(self::ADVERTISER_ID))
            ->willReturn($advertiser->reveal())
            ->shouldBeCalled();

        $this->campaignRepository->findOneByOrigin(Argument::type('integer'))
            ->willReturn($campaign->reveal())
            ->shouldBeCalled();

        $this->campaignRepository->save(Argument::type(Campaign::class))
            ->shouldBeCalled();

        $synchronizeCampaignCommand = new SynchronizeCampaignCommand(
            self::CAMPAIGN_ID,
            self::CAMPAIGN_NAME,
            self::ADVERTISER_ID
        );

        $synchronizeCampaignHandler = new SynchronizeCampaignHandler(
            $this->campaignRepository->reveal(),
            $this->advertiserRepository->reveal()
        );

        $synchronizeCampaignHandler->handle($synchronizeCampaignCommand);
    }
}