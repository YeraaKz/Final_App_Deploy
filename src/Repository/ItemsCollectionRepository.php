<?php

namespace App\Repository;

use App\Entity\ItemsCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ItemsCollection>
 */
class ItemsCollectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemsCollection::class);
    }

    public function findBiggestCollections(int $limit): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.items', 'i')
            ->groupBy('c.id')
            ->orderBy('COUNT(i)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
