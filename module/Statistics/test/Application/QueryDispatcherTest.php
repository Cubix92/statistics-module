<?php

declare(strict_types=1);

namespace StatisticsTest\Application;

use Statistics\Application\Query\ListStatisticsHandler;
use Statistics\Application\Query\ListStatisticsQuery;
use Statistics\Application\QueryBus;
use Statistics\Application\QueryHandlerNotFoundException;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class QueryDispatcherTest extends AbstractHttpControllerTestCase
{
    const ADVERTISER_ID = 101;
    const ADVERTISER_NAME = 'example_advertiser_name';

    /** QueryBus $queryBus */
    private $queryBus;

    /** ListStatisticsHandler $listStatisticsHandler */
    private $listStatisticsHandler;

    protected function setUp()
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();

        $this->listStatisticsHandler =  $this->prophesize(ListStatisticsHandler::class);
        $this->queryBus = $this->getApplicationServiceLocator()->get(QueryBus::class);

        $services = $this->getApplicationServiceLocator();

        $services->setAllowOverride(true);
        $services->setService(ListStatisticsHandler::class, $this->listStatisticsHandler->reveal());
        $services->setAllowOverride(false);
    }

    public function testQueryDispatchesProperly()
    {
        $listStatisticsQuery = new ListStatisticsQuery(new \DateTime(), new \DateTime(), []);

        $this->listStatisticsHandler
            ->handle($listStatisticsQuery)
            ->shouldBeCalled();

        $this->queryBus->dispatch($listStatisticsQuery);
    }

    public function testExceptionIsThrownWhenExecuteNonExistedCommand()
    {
        $nonExistedQuery = new NonExistedQuery();

        $this->expectException(QueryHandlerNotFoundException::class);
        $this->expectExceptionMessage(get_class($nonExistedQuery));

        $this->queryBus->dispatch($nonExistedQuery);
    }
}

class NonExistedQuery
{
}