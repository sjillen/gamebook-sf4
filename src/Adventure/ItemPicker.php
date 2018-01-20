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
use App\Entity\SpecialItem;
use App\Entity\Weapon;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class ItemPicker
{
    const WEAPONSKILL_MESSAGE = "Weaponskill ! +2 ability !";
    const ONE_WEAPON_CARRIED = "One weapon carried: +4 ability!";
    const WEAPON_FULL = "You cannot carry more weapons!";

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function pickupGold(Hero $hero, $gold) : string
    {
        $currentGold = $hero->getGold();
        $hero->setGold($currentGold + $gold);
        $this->em->persist($hero);
        $this->em->flush();
        return "You picked " . $gold . " golds";
    }

    public static function pickUpWeapon(Hero $hero, Weapon $weaponPickable) : ?array
    {
        $weapons = $hero->getWeapons();
        $maxCarry = $hero->getStory()->getRuleset()->getMaxWeaponCarried();
        $spaceLeft = $maxCarry - count($weapons);
        $messages = [];

        if ($spaceLeft === $maxCarry) {
;
            $hero->setAbility($hero->getAbility() + 4);

            $hero->addWeapon($weaponPickable);
            $weaponskill = Alteration::weaponSkillBonus($hero, $weaponPickable);

            $message = "You equipped " . $weaponPickable->getName();
            $messages[] = $message;
            $messages[] = self::ONE_WEAPON_CARRIED;
            if ($weaponskill) {
                $messages[] = self::WEAPONSKILL_MESSAGE;
            }
        } else if ($spaceLeft > 0) {
            if (!$weapons->contains($weaponPickable)) {
                $hero->addWeapon($weaponPickable);
                Alteration::weaponSkillBonus($hero, $weaponPickable);
                $message = "You equipped " . $weaponPickable->getName();
                $messages[] = $message;
            } else {
                $message = null;
            }
        }else if ($spaceLeft === 0) {

            $message = null;

        }
        if (count($messages) === 0) {
            return null;
        }else {
            return $messages;
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
        $currentCapacity = self::getCurrentStock($hero);

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

    public function pickUpSpecialItem(Hero $hero, SpecialItem $item) : bool
    {
        $slot = $item->getSlot();
        $specialItems = $hero->getSpecialItems();

        //if the pickable item has no specific slot
        if(!isset($slot) || $slot === "") {
            foreach ($specialItems as $specialItem) {
                if($item->getId() === $specialItem->getId()) {
                    return false;
                }
            }
            $hero->addSpecialItem($item);
            Alteration::equippedSpecialItem($hero, $item);
        }elseif(isset($slot) || $slot !== "") {
            //check what slots are taken on hero
            foreach ($specialItems as $specialItem ) {
                $slotHero = $specialItem->getSlot();
                //In case the corresponding slot is taken
                if($slotHero === $slot) {
                    return false;
                }
            }
            //add item to inventory
            $hero->addSpecialItem($item);
            Alteration::equippedSpecialItem($hero, $item);
        }
        return true;
    }

    public static function getCurrentStock(Hero $hero) : ?int
    {
        $backpackItems = $hero->getBackpackItems();
        $stock = 0;
        foreach ($backpackItems as $item) {
            $stock += $item->getStock();
        }
        return $stock;
    }
}