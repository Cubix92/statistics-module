<?php

declare(strict_types=1);

namespace Statistics\Infrastructure\Repository;

use Application\Service\Query\Parameters;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Statistics\Domain\Model\Statistics;
use Statistics\Domain\Repository\StatisticsRepositoryInterface;

class StatisticsRepository extends EntityRepository implements StatisticsRepositoryInterface
{
    private $columns = [
        0 => 's.date',
        1 => 'a.name',
        2 => 'c.name',
        3 => 'b.description',
        4 => 's.clicks',
        5 => 's.uniqueClicks',
        6 => 's.impressions',
        7 => 's.uniqueImpressions'
    ];

    public function save(Statistics $statistics): void
    {
        $this->getEntityManager()->persist($statistics);
        try {
            $this->getEntityManager()->flush();
        } catch (\Exception $e) {
            var_dump($e->getMessage());die;
        }
    }

    public function findOneByBannerOriginAndDate(int $origin, \DateTime $date): ?Statistics
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->join('s.banner', 'b')
            ->where('b.origin = :origin AND s.date = :date')
            ->setParameter('origin', $origin)
            ->setParameter('date', $date->format('Y-m-d'));

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function findForList(): Collection
    {
        // TODO: Implement findForList() method.
    }

    public function searchByParams(Parameters $parameters)
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->join('s.banner', 'b')
            ->join('b.campaign', 'c')
            ->join('c.advertiser', 'a');

        $this->addFilters($queryBuilder, $parameters);

        $queryBuilder->setFirstResult($parameters->getOffset())
            ->setMaxResults($parameters->getLimit())
            ->orderBy($this->columns[$parameters->getOrderColumn()], $parameters->getOrderDirection());

        return $queryBuilder->getQuery()->getResult();
    }

    public function countByParams(Parameters $parameters): int
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->select('COUNT(s)')
            ->join('s.banner', 'b')
            ->join('b.campaign', 'c')
            ->join('c.advertiser', 'a');

        $this->addFilters($queryBuilder, $parameters);

        try {
            return (int) $queryBuilder->getQuery()->getSingleScalarResult();
        } catch (NoResultException $e) {
            return 0;
        }
    }

    public function addFilters(QueryBuilder &$queryBuilder, Parameters $parameters): void
    {
        $expressionBuilder = $this->getEntityManager()->getExpressionBuilder();
        $expression = $expressionBuilder->andX();

        if ($parameters->hasFilterParam('banner')) {
            $expression->add($expressionBuilder->eq('b.id', ':banner'));
            $queryBuilder->setParameter('banner', $parameters->getFilterParam('banner'));
        }

        if ($parameters->hasFilterParam('campaign')) {
            $expression->add($expressionBuilder->eq('c.id', ':campaign'));
            $queryBuilder->setParameter('campaign', $parameters->getFilterParam('campaign'));
        }

        if ($parameters->hasFilterParam('daterange')) {
            $expression->add($expressionBuilder->between('s.date', ':from', ':to'));

            list($dateFrom, $dateTo) = explode(' - ', $parameters->getFilterParam('daterange'));
            $queryBuilder->setParameter('from', (new \DateTime($dateFrom))->format('Y-m-d'));
            $queryBuilder->setParameter('to', (new \DateTime($dateTo))->format('Y-m-d'));
        }

        if ($expression->count()) {
            $queryBuilder->where($expression);
        }
    }
}