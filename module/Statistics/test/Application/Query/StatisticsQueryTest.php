<?php

declare(strict_types=1);

namespace StatisticsTest\Application\Command;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Statistics\Application\Query\ListStatisticsHandler;
use Statistics\Application\Query\ListStatisticsQuery;
use Statistics\Domain\Repository\StatisticsRepositoryInterface;
use StatisticsTest\Domain\StatisticsTest;

class StatisticsQueryTest extends TestCase
{
    /** @var StatisticsRepositoryInterface $statisticsRepository */
    private $statisticsRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->statisticsRepository = $this->prophesize(StatisticsRepositoryInterface::class);
    }

    public function testListStatisticsQueryExecutesProperly()
    {
        $this->statisticsRepository->findForList(
            Argument::type(\DateTime::class),
            Argument::type(\DateTime::class),
            Argument::type('array')
        )
            ->willReturn(new ArrayCollection([
                StatisticsTest::createStatisticsFake()
            ]))
            ->shouldBeCalled();

        $listStatisticsQuery = new ListStatisticsQuery(
            new \DateTime(),
            new \DateTime(),
            []
        );

        $createStatisticsHandler = new ListStatisticsHandler(
            $this->statisticsRepository->reveal()
        );

        $data = $createStatisticsHandler->handle($listStatisticsQuery);

        $this->assertIsArray($data);
        $statisticsRow = current($data);

        $this->assertIsString($statisticsRow['date']);
        $this->assertIsInt($statisticsRow['clicks']);
        $this->assertIsInt($statisticsRow['unique_clicks']);
        $this->assertIsInt($statisticsRow['impressions']);
        $this->assertIsInt($statisticsRow['unique_impressions']);
    }
}