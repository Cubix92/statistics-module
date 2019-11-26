<?php

declare(strict_types=1);

namespace Statistics\Presentation\Factory;

use Interop\Container\ContainerInterface;
use Statistics\Application\CommandBus;
use Statistics\Domain\Service\AdvertiserServiceInterface;
use Statistics\Domain\Service\BannerServiceInterface;
use Statistics\Domain\Service\CampaignServiceInterface;
use Statistics\Presentation\Console\BannerSynchronizeController;
use Zend\ServiceManager\Factory\FactoryInterface;

class BannerSynchronizeControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): BannerSynchronizeController
    {
        $commandBus = $container->get(CommandBus::class);
        $advertiserService = $container->get(AdvertiserServiceInterface::class);
        $campaignService = $container->get(CampaignServiceInterface::class);
        $bannerService = $container->get(BannerServiceInterface::class);

        return new BannerSynchronizeController($commandBus, $advertiserService, $campaignService, $bannerService);
    }
}