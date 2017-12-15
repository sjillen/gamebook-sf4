<?php

namespace App\Repository;

use App\Entity\Chapter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ChapterRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Chapter::class);
    }

    public function findStarterByStory($story)
    {
        return $this->createQueryBuilder('c')
            ->where('c.story = :story')->setParameter('story', $story)
            ->andWhere('c.type = :type')->setParameter('type', "standard")
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }
}
