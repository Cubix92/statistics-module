<?php

declare(strict_types=1);

namespace Statistics\Infrastructure\Repository;

use Doctrine\ORM\EntityRepository;
use Statistics\Domain\Model\Banner;
use Statistics\Domain\Repository\BannerRepositoryInterface;

class BannerRepository extends EntityRepository implements BannerRepositoryInterface
{
    public function save(Banner $banner): void
    {
        $this->getEntityManager()->persist($banner);
        $this->getEntityManager()->flush();
    }

    public function findOneByOrigin(int $origin): ?Banner
    {
        $queryBuilder = $this->createQueryBuilder('b')
            ->where('b.origin = :origin')
            ->setParameter('origin', $origin);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}