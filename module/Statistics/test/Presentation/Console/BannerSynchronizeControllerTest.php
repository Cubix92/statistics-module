<?php

declare(strict_types=1);

namespace StatisticsTest\Presentation\Console\Statistic;

use Prophecy\Argument;
use Statistics\Application\Command\SynchronizeBannerCommand;
use Statistics\Application\CommandBus;
use Statistics\Domain\Service\AdvertiserServiceInterface;
use Statistics\Domain\Service\BannerServiceInterface;
use Statistics\Domain\Service\CampaignServiceInterface;
use Statistics\Presentation\Console\BannerSynchronizeController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase;

class BannerSynchronizeControllerTest extends AbstractConsoleControllerTestCase
{
    const BANNER_ROWS = [
        [
            'description' => 'first_test',
            'bannerid' => 123
        ],
        [
            'description' => 'second_test',
            'bannerid' => 124
        ]
    ];

    private $commandBus;

    private $advertiserService;

    private $campaignService;

    private $bannerService;

    protected function setUp()
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();

        $sharedManager = $this->getApplication()->getEventManager()->getSharedManager();
        $sharedManager->clearListeners(AbstractActionController::class);

        $this->commandBus = $this->prophesize(CommandBus::class);
        $this->advertiserService = $this->prophesize(AdvertiserServiceInterface::class);
        $this->campaignService = $this->prophesize(CampaignServiceInterface::class);
        $this->bannerService = $this->prophesize(BannerServiceInterface::class);

        $services = $this->getApplicationServiceLocator();
        $services->setAllowOverride(true);
        $services->setService(AdvertiserServiceInterface::class, $this->advertiserService->reveal());
        $services->setService(CampaignServiceInterface::class, $this->campaignService->reveal());
        $services->setService(BannerServiceInterface::class, $this->bannerService->reveal());
        $services->setService(CommandBus::class, $this->commandBus->reveal());
        $services->setAllowOverride(false);
    }

    public function testBannerSynchronizeCanBeAccessed()
    {
        $this->advertiserService->fetchAdvertisers()
            ->willReturn(AdvertiserSynchronizeControllerTest::ADVERTISER_ROWS)
            ->shouldBeCalled();

        $this->campaignService->fetchCampaigns(Argument::type('integer'))
            ->willReturn(CampaignSynchronizeControllerTest::CAMPAIGN_ROWS)
            ->shouldBeCalled();

        $this->bannerService->fetchBanners(Argument::type('integer'))
            ->willReturn(self::BANNER_ROWS)
            ->shouldBeCalled();

        $this->commandBus->execute(Argument::type(SynchronizeBannerCommand::class))
            ->shouldBeCalled();

        $this->dispatch('synchronize banners');

        $this->assertResponseStatusCode(0);
        $this->assertModuleName('Statistics');
        $this->assertControllerName(BannerSynchronizeController::class);
        $this->assertControllerClass('BannerSynchronizeController');
        $this->assertMatchedRouteName('synchronize-banners');
    }
}