<?php

namespace App\Repository;

use App\Entity\Weaponskill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class WeaponskillRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Weaponskill::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('w')
            ->where('w.something = :value')->setParameter('value', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
