<?php

declare(strict_types=1);

namespace StatisticsTest\Application\Command;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Statistics\Application\Command\BannerNotFoundException;
use Statistics\Application\Command\CreateStatisticsCommand;
use Statistics\Application\Command\CreateStatisticsHandler;
use Statistics\Domain\Exception\StatisticsDuplicateException;
use Statistics\Domain\Model\Banner;
use Statistics\Domain\Model\Statistics;
use Statistics\Domain\Repository\BannerRepositoryInterface;
use Statistics\Domain\Repository\StatisticsRepositoryInterface;

class StatisticsCommandTest extends TestCase
{
    const BANNER_ID = 101;
    const CLICKS = 500;
    const UNIQUE_CLICKS = 300;
    const IMPRESSIONS = 2000;
    const UNIQUE_IMPRESSIONS = 1000;

    /** @var BannerRepositoryInterface $bannerRepository */
    private $bannerRepository;

    /** @var StatisticsRepositoryInterface $statisticsRepository */
    private $statisticsRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->bannerRepository = $this->prophesize(BannerRepositoryInterface::class);
        $this->statisticsRepository = $this->prophesize(StatisticsRepositoryInterface::class);
    }

    public function testCreateStatisticsCommandExecutesProperly()
    {
        /** @var Banner $banner */
        $banner = $this->prophesize(Banner::class)->reveal();

        $this->bannerRepository->findOneByOrigin(Argument::type('integer'))
            ->willReturn($banner)
            ->shouldBeCalled();

        $this->statisticsRepository->findOneByBannerOriginAndDate(Argument::type('integer'), Argument::type(\DateTime::class))
            ->willReturn(null)
            ->shouldBeCalled();

        $this->statisticsRepository->save(Argument::type(Statistics::class))
            ->shouldBeCalled();

        $createStatisticsCommand = new CreateStatisticsCommand(
            new \DateTime(),
            self::BANNER_ID,
            self::CLICKS,
            self::UNIQUE_CLICKS,
            self::IMPRESSIONS,
            self::UNIQUE_IMPRESSIONS
        );

        $createStatisticsHandler = new CreateStatisticsHandler(
            $this->statisticsRepository->reveal(),
            $this->bannerRepository->reveal()
        );

        $createStatisticsHandler->handle($createStatisticsCommand);
    }

    public function testExceptionIsThrownWhenCreateStatisticsWithNonExistedBanner()
    {
        $now = new \DateTime();

        /** @var Statistics $statistics */
        $statistics = $this->prophesize(Statistics::class)->reveal();

        $this->statisticsRepository->findOneByBannerOriginAndDate(Argument::type('integer'), Argument::type(\DateTime::class))
            ->willReturn($statistics)
            ->shouldBeCalled();

        $this->expectException(StatisticsDuplicateException::class);
        $this->expectExceptionMessage("On {$now->format('Y-m-d')} statistics were added and cannot be overwritten.");

        $createStatisticsCommand = new CreateStatisticsCommand(
            new \DateTime(),
            self::BANNER_ID,
            self::CLICKS,
            self::UNIQUE_CLICKS,
            self::IMPRESSIONS,
            self::UNIQUE_IMPRESSIONS
        );

        $createStatisticsHandler = new CreateStatisticsHandler(
            $this->statisticsRepository->reveal(),
            $this->bannerRepository->reveal()
        );

        $createStatisticsHandler->handle($createStatisticsCommand);
    }

    public function testExceptionIsThrownWhenCreateStatisticsWithDuplicateDate()
    {
        $this->bannerRepository->findOneByOrigin(Argument::type('integer'))
            ->willReturn(null)
            ->shouldBeCalled();

        $this->statisticsRepository->findOneByBannerOriginAndDate(Argument::type('integer'), Argument::type(\DateTime::class))
            ->willReturn(null)
            ->shouldBeCalled();

        $this->expectException(BannerNotFoundException::class);

        $createStatisticsCommand = new CreateStatisticsCommand(
            new \DateTime(),
            self::BANNER_ID,
            self::CLICKS,
            self::UNIQUE_CLICKS,
            self::IMPRESSIONS,
            self::UNIQUE_IMPRESSIONS
        );

        $createStatisticsHandler = new CreateStatisticsHandler(
            $this->statisticsRepository->reveal(),
            $this->bannerRepository->reveal()
        );

        $createStatisticsHandler->handle($createStatisticsCommand);
    }
}