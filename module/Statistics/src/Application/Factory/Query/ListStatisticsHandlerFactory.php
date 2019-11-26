<?php

declare(strict_types=1);

namespace Statistics\Application\Factory\Query;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Statistics\Application\Query\ListStatisticsHandler;
use Statistics\Domain\Model\Statistics;
use Statistics\Infrastructure\Repository\StatisticsRepository;
use Zend\ServiceManager\Factory\FactoryInterface;

class ListStatisticsHandlerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): ListStatisticsHandler
    {
        /** @var StatisticsRepository $statisticsRepository */
        $statisticsRepository = $container->get(EntityManager::class)->getRepository(Statistics::class);

        return new ListStatisticsHandler($statisticsRepository);
    }
}