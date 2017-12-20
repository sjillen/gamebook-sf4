<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 20/12/2017
 * Time: 23:43
 */

namespace App\Adventure;


use App\Entity\Hero;
use App\Entity\SpecialItem;
use Doctrine\ORM\EntityManagerInterface;

class ItemPicker
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function pickUpSpecialItem(Hero $hero, SpecialItem $item)
    {
        $slot = $item->getSlot();
        //if the pickable item has no specific slot
        if(!isset($slot)) {
            $hero->addSpecialItem($item);
        }elseif(isset($slot)) {
            //check what slots are taken on hero
            $specialItems = $hero->getSpecialItems();
            foreach ($specialItems as $specialItem ) {
                $slotHero = $specialItem->getSlot();
                //In case the corresponding slot is taken
                if($slotHero === $slot) {
                    //Remove the object from slot
                    $hero->removeSpecialItem($specialItem);
                }
            }
            //add item to inventory
            $hero->addSpecialItem($item);
        }
        $this->em->persist($hero);
        $this->em->flush();
    }
}