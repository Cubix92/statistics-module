<?php

declare(strict_types=1);

namespace Statistics\Application\Command;

use Statistics\Domain\Model\Banner;
use Statistics\Domain\Model\Campaign;
use Statistics\Domain\Repository\BannerRepositoryInterface;
use Statistics\Domain\Repository\CampaignRepositoryInterface;

final class SynchronizeBannerHandler
{
    private $bannerRepository;

    private $campaignRepository;

    public function __construct(
        BannerRepositoryInterface $bannerRepository,
        CampaignRepositoryInterface $campaignRepository
    ) {
        $this->bannerRepository = $bannerRepository;
        $this->campaignRepository = $campaignRepository;
    }

    public function handle(SynchronizeBannerCommand $command) : void
    {
        /** @var Banner $banner */
        $banner = $this->bannerRepository->findOneByOrigin($command->origin);

        /** @var Campaign $campaign */
        $campaign = $this->campaignRepository->findOneByOrigin($command->campaignId);

        if ($banner) {
            $banner->setDescription($command->description);
        } else {
            $banner = new Banner(
                $command->origin,
                $command->description
            );
        }

        $banner->setCampaign($campaign);

        $this->bannerRepository->save($banner);
    }
}