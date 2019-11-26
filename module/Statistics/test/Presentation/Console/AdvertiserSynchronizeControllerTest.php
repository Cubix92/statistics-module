<?php

declare(strict_types=1);

namespace StatisticsTest\Presentation\Console\Statistic;

use Prophecy\Argument;
use Statistics\Application\Command\SynchronizeAdvertiserCommand;
use Statistics\Application\CommandBus;
use Statistics\Domain\Service\AdvertiserServiceInterface;
use Statistics\Presentation\Console\AdvertiserSynchronizeController;
use Zend\Mvc\Console\Controller\AbstractConsoleController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase;

class AdvertiserSynchronizeControllerTest extends AbstractConsoleControllerTestCase
{
    const ADVERTISER_ROWS = [
        [
            'clientname' => 'first_test',
            'clientid' => 123
        ],
        [
            'clientname' => 'second_test',
            'clientid' => 124
        ],
    ];

    private $commandBus;

    private $advertiserService;

    protected function setUp()
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();

        $sharedManager = $this->getApplication()->getEventManager()->getSharedManager();
        $sharedManager->clearListeners(AbstractConsoleController::class);

        $this->advertiserService = $this->prophesize(AdvertiserServiceInterface::class);
        $this->commandBus = $this->prophesize(CommandBus::class);

        $services = $this->getApplicationServiceLocator();
        $services->setAllowOverride(true);
        $services->setService(AdvertiserServiceInterface::class, $this->advertiserService->reveal());
        $services->setService(CommandBus::class, $this->commandBus->reveal());
        $services->setAllowOverride(false);
    }

    public function testAdvertiserSynchronizeCanBeAccessed()
    {
        $this->advertiserService->fetchAdvertisers()
            ->willReturn(self::ADVERTISER_ROWS)
            ->shouldBeCalled();

        $this->commandBus->execute(Argument::type(SynchronizeAdvertiserCommand::class))
            ->shouldBeCalled();

        $this->dispatch('synchronize advertisers');

        $this->assertResponseStatusCode(0);
        $this->assertModuleName('Statistics');
        $this->assertControllerName(AdvertiserSynchronizeController::class);
        $this->assertControllerClass('AdvertiserSynchronizeController');
        $this->assertMatchedRouteName('synchronize-advertisers');
    }
}