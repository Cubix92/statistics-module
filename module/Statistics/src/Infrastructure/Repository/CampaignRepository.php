<?php

declare(strict_types=1);

namespace Statistics\Infrastructure\Repository;

use Doctrine\ORM\EntityRepository;
use Statistics\Domain\Model\Campaign;
use Statistics\Domain\Repository\CampaignRepositoryInterface;

class CampaignRepository extends EntityRepository implements CampaignRepositoryInterface
{
    public function save(Campaign $campaign): void
    {
        $this->getEntityManager()->persist($campaign);
        $this->getEntityManager()->flush();
    }

    public function findOneByOrigin(int $origin): ?Campaign
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->where('c.origin = :origin')
            ->setParameter('origin', $origin);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function findOneById(int $id): ?Campaign
    {
        return parent::findOneById($id);
    }
}