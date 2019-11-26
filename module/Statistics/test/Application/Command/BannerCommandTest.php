<?php

declare(strict_types=1);

namespace StatisticsTest\Application\Command;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Statistics\Application\Command\SynchronizeBannerCommand;
use Statistics\Application\Command\SynchronizeBannerHandler;
use Statistics\Domain\Model\Banner;
use Statistics\Domain\Model\Campaign;
use Statistics\Domain\Repository\BannerRepositoryInterface;
use Statistics\Domain\Repository\CampaignRepositoryInterface;

class BannerCommandTest extends TestCase
{
    const BANNER_ID = 101;
    const BANNER_NAME = 'example_banner_name';
    const CAMPAIGN_ID = 202;

    /** @var CampaignRepositoryInterface $campaignRepository */
    private $campaignRepository;

    /** @var BannerRepositoryInterface $bannerRepository */
    private $bannerRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->campaignRepository = $this->prophesize(CampaignRepositoryInterface::class);
        $this->bannerRepository = $this->prophesize(BannerRepositoryInterface::class);
    }

    public function testCreateInSynchronizeBannerCommandExecutesProperly()
    {
        /** @var Campaign $campaign */
        $campaign = $this->prophesize(Campaign::class);

        $this->campaignRepository->findOneByOrigin(Argument::is(self::CAMPAIGN_ID))
            ->willReturn($campaign->reveal())
            ->shouldBeCalled();

        $this->bannerRepository->findOneByOrigin(Argument::type('integer'))
            ->willReturn(null)
            ->shouldBeCalled();

        $this->bannerRepository->save(Argument::type(Banner::class))
            ->shouldBeCalled();

        $synchronizeBannerCommand = new SynchronizeBannerCommand(
            self::BANNER_ID,
            self::BANNER_NAME,
            self::CAMPAIGN_ID
        );

        $synchronizeBannerHandler = new SynchronizeBannerHandler(
            $this->bannerRepository->reveal(),
            $this->campaignRepository->reveal()
        );

        $synchronizeBannerHandler->handle($synchronizeBannerCommand);
    }

    public function testUpdateInSynchronizeBannerCommandExecutesProperly()
    {
        /** @var Banner $banner */
        $banner = $this->prophesize(Banner::class);

        $banner->setDescription(Argument::type('string'))
            ->shouldBeCalled();

        $banner->setCampaign(Argument::type(Campaign::class))
            ->shouldBeCalled();

        /** @var Campaign $campaign */
        $campaign = $this->prophesize(Campaign::class);

        $this->campaignRepository->findOneByOrigin(Argument::is(self::CAMPAIGN_ID))
            ->willReturn($campaign->reveal())
            ->shouldBeCalled();

        $this->bannerRepository->findOneByOrigin(Argument::type('integer'))
            ->willReturn($banner)
            ->shouldBeCalled();

        $this->bannerRepository->save(Argument::type(Banner::class))
            ->shouldBeCalled();

        $synchronizeBannerCommand = new SynchronizeBannerCommand(
            self::BANNER_ID,
            self::BANNER_NAME,
            self::CAMPAIGN_ID
        );

        $synchronizeBannerHandler = new SynchronizeBannerHandler(
            $this->bannerRepository->reveal(),
            $this->campaignRepository->reveal()
        );

        $synchronizeBannerHandler->handle($synchronizeBannerCommand);
    }
}