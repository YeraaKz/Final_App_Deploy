<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\Like;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Like>
 */
class LikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Like::class);
    }

    public function findByUserAndItem(User $user, Item $item): ?Like
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.user = :user')
            ->andWhere('l.item = :item')
            ->setParameter('user', $user)
            ->setParameter('item', $item)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
