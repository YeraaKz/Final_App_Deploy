<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findByCreatedDateAsc(int $itemId)
    {
        return $this->createQueryBuilder('c')
            ->select('c.id, c.content, u.email as author, c.createdAt')
            ->innerJoin('c.user', 'u')
            ->where('c.item = :itemId')
            ->setParameter('itemId', $itemId)
            ->orderBy('c.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
