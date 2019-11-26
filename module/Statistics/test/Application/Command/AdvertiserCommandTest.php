<?php

declare(strict_types=1);

namespace StatisticsTest\Application\Command;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Statistics\Application\Command\SynchronizeAdvertiserCommand;
use Statistics\Application\Command\SynchronizeAdvertiserHandler;
use Statistics\Domain\Model\Advertiser;
use Statistics\Domain\Repository\AdvertiserRepositoryInterface;

class AdvertiserCommandTest extends TestCase
{
    const ADVERTISER_ID = 101;
    const ADVERTISER_NAME = 'example_advertiser_name';

    /** @var AdvertiserRepositoryInterface $advertiserRepository */
    private $advertiserRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->advertiserRepository = $this->prophesize(AdvertiserRepositoryInterface::class);
    }

    public function testCreateInSynchronizeAdvertiserCommandExecutesProperly()
    {
        $this->advertiserRepository->findOneByOrigin(Argument::type('integer'))
            ->willReturn(null)
            ->shouldBeCalled();

        $this->advertiserRepository->save(Argument::type(Advertiser::class))
            ->shouldBeCalled();

        $synchronizeAdvertiserCommand = new SynchronizeAdvertiserCommand(self::ADVERTISER_ID, self::ADVERTISER_NAME);
        $synchronizeAdvertiserHandler = new SynchronizeAdvertiserHandler($this->advertiserRepository->reveal());
        $synchronizeAdvertiserHandler->handle($synchronizeAdvertiserCommand);
    }

    public function testUpdateInSynchronizeAdvertiserCommandExecutesProperly()
    {
        /** @var Advertiser $advertiser */
        $advertiser = $this->prophesize(Advertiser::class);

        $advertiser->setName(Argument::type('string'))
            ->shouldBeCalled();

        $this->advertiserRepository->findOneByOrigin(Argument::type('integer'))
            ->willReturn($advertiser->reveal())
            ->shouldBeCalled();

        $this->advertiserRepository->save(Argument::type(Advertiser::class))
            ->shouldBeCalled();

        $synchronizeAdvertiserCommand = new SynchronizeAdvertiserCommand(self::ADVERTISER_ID, self::ADVERTISER_NAME);
        $synchronizeAdvertiserHandler = new SynchronizeAdvertiserHandler($this->advertiserRepository->reveal());
        $synchronizeAdvertiserHandler->handle($synchronizeAdvertiserCommand);
    }
}