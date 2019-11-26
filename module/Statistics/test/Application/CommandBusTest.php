<?php

declare(strict_types=1);

namespace StatisticsTest\Application;

use Statistics\Application\Command\SynchronizeAdvertiserCommand;
use Statistics\Application\Command\SynchronizeAdvertiserHandler;
use Statistics\Application\CommandBus;
use Statistics\Application\CommandHandlerNotFoundException;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class CampaignCommandTest extends AbstractHttpControllerTestCase
{
    const ADVERTISER_ID = 101;
    const ADVERTISER_NAME = 'example_advertiser_name';

    /** @var SynchronizeAdvertiserHandler $synchronizeAdvertiserHandler */
    private $synchronizeAdvertiserHandler;

    /** CommandBus $commandBus */
    private $commandBus;

    protected function setUp()
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();

        $this->synchronizeAdvertiserHandler = $this->prophesize(SynchronizeAdvertiserHandler::class);
        $this->commandBus = $this->getApplicationServiceLocator()->get(CommandBus::class);

        $services = $this->getApplicationServiceLocator();

        $services->setAllowOverride(true);
        $services->setService(SynchronizeAdvertiserHandler::class, $this->synchronizeAdvertiserHandler->reveal());
        $services->setAllowOverride(false);
    }

    public function testCommandExecutesProperly()
    {
        $synchronizeAdvertiserCommand = new SynchronizeAdvertiserCommand(self::ADVERTISER_ID, self::ADVERTISER_NAME);

        $this->synchronizeAdvertiserHandler
            ->handle($synchronizeAdvertiserCommand)
            ->shouldBeCalled();

        $this->commandBus->execute($synchronizeAdvertiserCommand);
    }

    public function testExceptionIsThrownWhenExecuteNonExistedCommand()
    {
        $nonExistedCommand = new NonExistedCommand();

        $this->expectException(CommandHandlerNotFoundException::class);
        $this->expectExceptionMessage(get_class($nonExistedCommand));

        $this->commandBus->execute($nonExistedCommand);
    }
}

class NonExistedCommand
{
}