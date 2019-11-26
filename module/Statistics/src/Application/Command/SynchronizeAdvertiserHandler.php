<?php

declare(strict_types=1);

namespace Statistics\Application\Command;

use Statistics\Domain\Model\Advertiser;
use Statistics\Domain\Repository\AdvertiserRepositoryInterface;

class SynchronizeAdvertiserHandler
{
    private $advertiserRepository;

    public function __construct(AdvertiserRepositoryInterface $advertiserRepository)
    {
        $this->advertiserRepository = $advertiserRepository;
    }

    public function handle(SynchronizeAdvertiserCommand $command) : void
    {
        /** @var Advertiser $advertiser */
        $advertiser = $this->advertiserRepository->findOneByOrigin($command->origin);

        if ($advertiser) {
            $advertiser->setName($command->name);
        } else {
            $advertiser = new Advertiser(
                $command->origin,
                $command->name
            );
        }

        $this->advertiserRepository->save($advertiser);
    }
}