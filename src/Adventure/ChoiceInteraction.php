<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 20/12/2017
 * Time: 22:31
 */

namespace App\Adventure;


use App\Entity\Choice;
use App\Entity\Hero;
use App\Entity\SpecialItem;
use Doctrine\ORM\EntityManagerInterface;

class ChoiceInteraction
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function trade(Hero $hero, Choice $choice) : void
    {
        $item = $choice->getItemRequired();
        isset($item)
        ? $this->tradeItem($hero, $item)
        : $this->tradeGold($hero, $choice)
        ;
        $this->em->persist($hero);
        $this->em->flush();
    }

    public function tradeGold(Hero $hero, Choice $choice) : void
    {
        $goldRequired = $choice->getGoldRequired();
        $goldCarried = $hero->getGold();
        $hero->setGold($goldCarried - $goldRequired);
    }

    public function tradeItem(Hero $hero, SpecialItem $item) : void
    {
        $hero->removeSpecialItem($item);
    }
}