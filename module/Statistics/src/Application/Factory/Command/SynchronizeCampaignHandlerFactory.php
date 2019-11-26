<?php

declare(strict_types=1);

namespace Statistics\Application\Factory\Command;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Statistics\Application\Command\SynchronizeCampaignHandler;
use Statistics\Domain\Model\Advertiser;
use Statistics\Domain\Model\Campaign;
use Statistics\Domain\Repository\AdvertiserRepository;
use Statistics\Domain\Repository\CampaignRepository;
use Zend\ServiceManager\Factory\FactoryInterface;

class SynchronizeCampaignHandlerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): SynchronizeCampaignHandler
    {
        /** @var CampaignRepository $campaignRepository */
        $campaignRepository = $container->get(EntityManager::class)->getRepository(Campaign::class);

        /** @var AdvertiserRepository $advertiserRepository */
        $advertiserRepository = $container->get(EntityManager::class)->getRepository(Advertiser::class);

        return new SynchronizeCampaignHandler($campaignRepository, $advertiserRepository);
    }
}