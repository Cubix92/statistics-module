<?php

declare(strict_types=1);

namespace StatisticsTest\Presentation\Console\Statistic;

use Prophecy\Argument;
use Statistics\Application\Command\CreateStatisticsCommand;
use Statistics\Application\CommandBus;
use Statistics\Presentation\Api\StatisticsController;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class StatisticsControllerTest extends AbstractHttpControllerTestCase
{
    private $commandBus;

    protected function setUp()
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();

        $sharedManager = $this->getApplication()->getEventManager()->getSharedManager();
        $sharedManager->clearListeners(AbstractRestfulController::class);

        $this->commandBus = $this->prophesize(CommandBus::class);

        $services = $this->getApplicationServiceLocator();
        $services->setAllowOverride(true);
        $services->setService(CommandBus::class, $this->commandBus->reveal());
        $services->setAllowOverride(false);
    }

    public function testCreateStatisticsAfterValidPost()
    {
        $this->commandBus->execute(Argument::type(CreateStatisticsCommand::class))
            ->shouldBeCalled();

        $postData = [
            '21-01-1992' => [
                1234 => [
                    "clicks" => 200,
                    "unique_clicks" => 100,
                    "impressions" => 1000,
                    "unique_impressions" => 500
                ]
            ]
        ];

        $this->dispatch('/api/v1/statistics', 'POST', $postData);

        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Statistics');
        $this->assertControllerName(StatisticsController::class);
        $this->assertControllerClass('StatisticsController');
        $this->assertMatchedRouteName('api/statistics');
    }
}