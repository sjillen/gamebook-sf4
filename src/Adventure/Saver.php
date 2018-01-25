<?php

namespace App\Adventure;

use App\Entity\Chapter;
use App\Entity\Hero;
use App\Entity\Story;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class Saver
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function saveHero(Hero $hero, Chapter $chapter) : void
    {
        $hero->setChapter($chapter);
        $this->em->persist($hero);
        $this->em->flush();
    }

    public function loadHero (User $user, Story $story) : ?Hero
    {
        $hero = $this->em->getRepository(Hero::class)->findOneBy(["user" => $user, "story" => $story, "status" => Hero::ON_ADVENTURE]);
        if(isset($hero)) {
            return $hero;
        }else {
            return null;
        }

    }
}