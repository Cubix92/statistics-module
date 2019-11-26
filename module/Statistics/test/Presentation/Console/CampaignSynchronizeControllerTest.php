<?php

declare(strict_types=1);

namespace StatisticsTest\Presentation\Console\Statistic;

use Prophecy\Argument;
use Statistics\Application\Command\SynchronizeCampaignCommand;
use Statistics\Application\CommandBus;
use Statistics\Domain\Service\AdvertiserServiceInterface;
use Statistics\Domain\Service\CampaignServiceInterface;
use Statistics\Presentation\Console\CampaignSynchronizeController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase;

class CampaignSynchronizeControllerTest extends AbstractConsoleControllerTestCase
{
    const CAMPAIGN_ROWS = [
        [
            'campaignname' => 'first_test',
            'campaignid' => 123
        ],
        [
            'campaignname' => 'second_test',
            'campaignid' => 124
        ]
    ];

    private $commandBus;

    private $advertiserService;

    private $campaignService;

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

        $services = $this->getApplicationServiceLocator();

        $services->setAllowOverride(true);
        $services->setService(AdvertiserServiceInterface::class, $this->advertiserService->reveal());
        $services->setService(CampaignServiceInterface::class, $this->campaignService->reveal());
        $services->setService(CommandBus::class, $this->commandBus->reveal());
        $services->setAllowOverride(false);
    }

    public function testCampaignSynchronizeCanBeAccessed()
    {
        $this->advertiserService->fetchAdvertisers()
            ->willReturn(AdvertiserSynchronizeControllerTest::ADVERTISER_ROWS)
            ->shouldBeCalled();

        $this->campaignService->fetchCampaigns(Argument::type('integer'))
            ->willReturn(self::CAMPAIGN_ROWS)
            ->shouldBeCalled();

        $this->commandBus->execute(Argument::type(SynchronizeCampaignCommand::class))
            ->shouldBeCalled();

        $this->dispatch('synchronize campaigns');

        $this->assertResponseStatusCode(0);
        $this->assertModuleName('Statistics');
        $this->assertControllerName(CampaignSynchronizeController::class);
        $this->assertControllerClass('CampaignSynchronizeController');
        $this->assertMatchedRouteName('synchronize-campaigns');
    }
}