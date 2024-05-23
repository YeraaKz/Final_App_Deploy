<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\ItemsCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Item>
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findLatestItems(int $limit): array
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByCollection(ItemsCollection $collection, $sortField = 'name', $sortOrder = 'asc'): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.collection = :collection')
            ->setParameter('collection', $collection)
            ->orderBy('i.' . $sortField, $sortOrder)
            ->getQuery()
            ->getResult();

    }

}
