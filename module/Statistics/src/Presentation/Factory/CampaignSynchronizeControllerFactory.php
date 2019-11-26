<?php

declare(strict_types=1);

namespace Statistics\Presentation\Factory;

use Interop\Container\ContainerInterface;
use Statistics\Application\CommandBus;
use Statistics\Domain\Service\AdvertiserServiceInterface;
use Statistics\Domain\Service\CampaignServiceInterface;
use Statistics\Presentation\Console\CampaignSynchronizeController;
use Zend\ServiceManager\Factory\FactoryInterface;

class CampaignSynchronizeControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): CampaignSynchronizeController
    {
        $commandBus = $container->get(CommandBus::class);
        $advertiserService = $container->get(AdvertiserServiceInterface::class);
        $campaignService = $container->get(CampaignServiceInterface::class);

        return new CampaignSynchronizeController($commandBus, $advertiserService, $campaignService);
    }
}