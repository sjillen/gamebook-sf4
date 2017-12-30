<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 20/12/2017
 * Time: 23:43
 */

namespace App\Adventure;


use App\Entity\BackpackItem;
use App\Entity\ConsumableItem;
use App\Entity\Hero;
use App\Entity\Ruleset;
use App\Entity\SpecialItem;
use App\Entity\Weapon;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class ItemPicker
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function pickUpWeapon(Hero $hero, $idWeapon) : ?string
    {
        $weapons = $hero->getWeapons();
        $weaponPickable = $this->em->getRepository(Weapon::class)->find($idWeapon);
        if(false === $weapons->contains($weaponPickable)) {
            if(count($weapons) < $hero->getStory()->getRuleset()->getMaxWeaponCarried()) {
                $hero->addWeapon($weaponPickable);
                $this->em->persist($hero);
                $this->em->flush();
                return null;
            }else {
                return "No space available, please remove an equipped weapon first";
            }
        } else {
            return "Weapon already equipped!";
        }
    }

    public function pickUpConsumableItem(Hero $hero, ConsumableItem $item) : bool
    {
        $backpack = $hero->getBackpackItems();
        //Check how much space is available in the backpack
        $quantityAvailable = $this->checkStock($hero, $backpack, $item);
        if(isset($quantityAvailable)) {
            //if there is space, add the quantity of item
            $itemAdded = $this->checkBackpack($hero, $backpack, $item, $quantityAvailable);
            $this->em->persist($itemAdded);
            $this->em->flush();
            return true;
        }else {
            return false;
        }

    }

    /**
     * @param Hero $hero
     * @param Collection $backpack
     * @param ConsumableItem $item
     * @param $quantity
     * @return BackpackItem|null
     *
     * insert item in backpack
     */
    public function checkBackpack(Hero $hero, Collection $backpack, ConsumableItem $item, $quantity) : ?BackpackItem
    {
        //check which items are in the backpack
        $isItem = false;
        foreach ($backpack as $bpItem) {
            //If corresponding item already inside backpack
            if ($bpItem->getItem() == $item) {
                //update stock
                $bpItem->addStock($quantity);
                $isItem = true;
                return $bpItem;
            }
        }
        //If item not inside the backapck
        if (!$isItem) {
            //Insert it
            $newItem = new BackpackItem($hero, $item, $quantity);
            return $newItem;
        }
    }

    /**
     * @param Hero|null $hero
     * @param Collection $backpack
     * @param ConsumableItem $item
     * @return int|null
     *
     * set the item quantity to insert into backpack according to available space
     */
    public function checkStock(?Hero $hero, Collection $backpack, ConsumableItem $item) : ?int
    {
        $maxCapacity = $hero->getStory()->getRuleset()->getBackPackCapacity();
        $currentCapacity = 0;
        foreach ($backpack as $bpItem) {
            $currentCapacity += $bpItem->getStock();
        }
        $quantity = $item->getQuantity();
        if ($currentCapacity == $maxCapacity) {
            return null;
        } elseif ($currentCapacity + $quantity < $maxCapacity) {
            return $quantity;
        }elseif ($maxCapacity <= $currentCapacity + $quantity) {
            $quantity = $maxCapacity - $currentCapacity;
            return $quantity;
        }


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