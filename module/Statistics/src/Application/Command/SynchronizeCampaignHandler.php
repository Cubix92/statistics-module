<?php

declare(strict_types=1);

namespace Statistics\Application\Command;

use Statistics\Domain\Model\Advertiser;
use Statistics\Domain\Model\Campaign;
use Statistics\Domain\Repository\AdvertiserRepositoryInterface;
use Statistics\Domain\Repository\CampaignRepositoryInterface;

class SynchronizeCampaignHandler
{
    private $campaignRepository;

    private $advertiserRepository;

    public function __construct(
        CampaignRepositoryInterface $campaignRepository,
        AdvertiserRepositoryInterface $advertiserRepository
    ) {
        $this->campaignRepository = $campaignRepository;
        $this->advertiserRepository = $advertiserRepository;
    }

    public function handle(SynchronizeCampaignCommand $command) : void
    {
        /** @var Campaign $campaign */
        $campaign = $this->campaignRepository->findOneByOrigin($command->origin);

        /** @var Advertiser $advertiser */
        $advertiser = $this->advertiserRepository->findOneByOrigin($command->advertiserId);

        if ($campaign) {
            $campaign->setName($command->name);
        } else {
            $campaign = new Campaign(
                $command->origin,
                $command->name
            );
        }

        $campaign->setAdvertiser($advertiser);

        $this->campaignRepository->save($campaign);
    }
}