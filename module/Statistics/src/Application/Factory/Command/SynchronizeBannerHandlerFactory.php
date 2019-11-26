<?php

declare(strict_types=1);

namespace Statistics\Application\Factory\Command;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Statistics\Application\Command\SynchronizeBannerHandler;
use Statistics\Domain\Model\Banner;
use Statistics\Domain\Model\Campaign;
use Statistics\Domain\Repository\AdvertiserRepository;
use Statistics\Domain\Repository\CampaignRepository;
use Zend\ServiceManager\Factory\FactoryInterface;

class SynchronizeBannerHandlerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): SynchronizeBannerHandler
    {
        /** @var AdvertiserRepository $advertiserRepository */
        $bannerRepository = $container->get(EntityManager::class)->getRepository(Banner::class);

        /** @var CampaignRepository $campaignRepository */
        $campaignRepository = $container->get(EntityManager::class)->getRepository(Campaign::class);

        return new SynchronizeBannerHandler($bannerRepository, $campaignRepository);
    }
}