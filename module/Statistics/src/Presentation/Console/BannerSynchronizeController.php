<?php

declare(strict_types=1);

namespace Statistics\Presentation\Console;

use Statistics\Application\Command\SynchronizeBannerCommand;
use Statistics\Application\CommandBus;
use Statistics\Domain\Service\AdvertiserServiceInterface;
use Statistics\Domain\Service\BannerServiceInterface;
use Statistics\Domain\Service\CampaignServiceInterface;
use Zend\Mvc\Console\Controller\AbstractConsoleController;
use Zend\ProgressBar\Adapter\Console;
use Zend\ProgressBar\ProgressBar;

class BannerSynchronizeController extends AbstractConsoleController
{
    public $commandBus;

    public $advertiserService;

    public $campaignService;

    public $bannerService;

    public function __construct(
        CommandBus $commandBus,
        AdvertiserServiceInterface $advertiserService,
        CampaignServiceInterface $campaignService,
        BannerServiceInterface $bannerService
    ) {
        $this->commandBus = $commandBus;
        $this->advertiserService = $advertiserService;
        $this->campaignService = $campaignService;
        $this->bannerService = $bannerService;
    }

    public function synchronizeAction(): void
    {
        $advertiserRows = $this->advertiserService->fetchAdvertisers();

        foreach ($advertiserRows as $advertiserRow) {
            $campaignRows = $this->campaignService->fetchCampaigns((int) $advertiserRow['clientid']);

            foreach ($campaignRows as $campaignRow) {
                $bannersRows = $this->bannerService->fetchBanners((int) $campaignRow['campaignid']);

                foreach ($bannersRows as $bannerRow) {
                    $synchronizeBannerCommand = new SynchronizeBannerCommand(
                        (int) $bannerRow['bannerid'],
                        $bannerRow['description'],
                        (int) $campaignRow['campaignid']
                    );

                    $this->commandBus->execute($synchronizeBannerCommand);
                }
            }
        }
    }
}