<?php

declare(strict_types=1);

namespace Statistics\Application\Factory\Command;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Statistics\Application\Command\CreateStatisticsHandler;
use Statistics\Domain\Model\Banner;
use Statistics\Domain\Model\Statistics;
use Statistics\Domain\Repository\BannerRepositoryInterface;
use Statistics\Domain\Repository\StatisticsRepositoryInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class CreateStatisticsHandlerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): CreateStatisticsHandler
    {
        /** @var BannerRepositoryInterface $bannerRepository */
        $bannerRepository = $container->get(EntityManager::class)->getRepository(Banner::class);

        /** @var StatisticsRepositoryInterface $statisticsRepository */
        $statisticsRepository = $container->get(EntityManager::class)->getRepository(Statistics::class);

        return new CreateStatisticsHandler($statisticsRepository, $bannerRepository);
    }
}