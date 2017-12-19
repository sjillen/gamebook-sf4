<?php

namespace App\Repository;

use App\Entity\Skill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SkillRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Skill::class);
    }

    public function findSkillsByStory($story)
    {
        return $qb = $this->createQueryBuilder("s")
            ->where("s.story = :story")
            ->setParameter("story", $story)
            ->andWhere("s INSTANCE OF App\Entity\Skill")
            ->getQuery()
            ->getResult()
            ;


    }
    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('t')
            ->where('t.something = :value')->setParameter('value', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
