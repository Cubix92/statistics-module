<?php

declare(strict_types=1);

namespace Statistics\Presentation\Console;

use Statistics\Application\Command\SynchronizeCampaignCommand;
use Statistics\Application\CommandBus;
use Statistics\Domain\Service\AdvertiserServiceInterface;
use Statistics\Domain\Service\CampaignServiceInterface;
use Zend\Mvc\Console\Controller\AbstractConsoleController;
use Zend\ProgressBar\Adapter\Console;
use Zend\ProgressBar\ProgressBar;

class CampaignSynchronizeController extends AbstractConsoleController
{
    public $commandBus;

    public $advertiserService;

    public $campaignService;

    public function __construct(
        CommandBus $commandBus,
        AdvertiserServiceInterface $advertiserService,
        CampaignServiceInterface $campaignService
    ) {
        $this->commandBus = $commandBus;
        $this->advertiserService = $advertiserService;
        $this->campaignService = $campaignService;
    }

    public function synchronizeAction(): void
    {
        $advertiserRows = $this->advertiserService->fetchAdvertisers();

        foreach ($advertiserRows as $advertiserRow) {
            $campaignRows = $this->campaignService->fetchCampaigns((int) $advertiserRow['clientid']);

            foreach ($campaignRows as $campaignRow) {
                $synchronizeCampaignCommand = new SynchronizeCampaignCommand(
                    (int) $campaignRow['campaignid'],
                    $campaignRow['campaignname'],
                    (int) $advertiserRow['clientid']
                );

                $this->commandBus->execute($synchronizeCampaignCommand);
            }
        }
    }
}