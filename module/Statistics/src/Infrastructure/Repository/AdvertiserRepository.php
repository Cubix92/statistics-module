<?php

declare(strict_types=1);

namespace Statistics\Infrastructure\Repository;

use Doctrine\ORM\EntityRepository;
use Statistics\Domain\Model\Advertiser;
use Statistics\Domain\Repository\AdvertiserRepositoryInterface;

class AdvertiserRepository extends EntityRepository implements AdvertiserRepositoryInterface
{
    public function save(Advertiser $advertiser): void
    {
        $this->getEntityManager()->persist($advertiser);
        $this->getEntityManager()->flush();
    }

    public function findOneByOrigin(int $origin): ?Advertiser
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->where('a.origin = :origin')
            ->setParameter('origin', $origin);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function findOneById(int $id): ?Advertiser
    {
        return parent::findOneById($id);
    }
}