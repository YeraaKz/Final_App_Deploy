<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function findByQuery(string $query)
    {

        return $this->createQueryBuilder('t')
            ->where('LOWER(t.name) LIKE :query')
            ->setParameter('query', '%' . strtolower($query) . '%')
            ->getQuery()
            ->getResult();
    }
}
