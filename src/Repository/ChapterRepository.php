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

    public function findStarterByStory($story, $type)
    {
        return $this->createQueryBuilder('c')
            ->where('c.story = :story')
            ->setParameter('story', $story)
            ->andWhere('c.type = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findWholeChapter($id)
    {
        return $this->createQueryBuilder('c')
            ->where("c.id = :id")
            ->setParameter("id", $id)
            ->leftJoin("c.weapons", "w")
            ->addSelect("w")
            ->leftJoin("c.specialItems", "si")
            ->addSelect("si")
            ->leftJoin("c.consumableItems", "ci")
            ->addSelect("ci")
            ->leftJoin("c.npcs", "npc")
            ->addSelect("npc")
            ->leftJoin("c.choices", "choices")
            ->addSelect("choices")
            ->getQuery()
            ->getOneOrNullResult()
        ;

    }
}
