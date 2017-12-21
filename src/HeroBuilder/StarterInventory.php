<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 16/12/2017
 * Time: 18:16
 */

namespace App\HeroBuilder;

use App\Entity\BackpackItem;
use App\Entity\ConsumableItem;
use App\Entity\Hero;
use App\Entity\SpecialItem;
use App\Entity\Story;
use App\Entity\Weapon;
use App\Dice\Dice;
use Doctrine\ORM\EntityManagerInterface;



class StarterInventory
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function setStarterInventory(Story $story, Hero $hero)
    {
        $this->starterWeapon($story, $hero);
        $this->starterItem($story, $hero);
    }

    public function starterWeapon(Story $story, Hero $hero)
    {
        $weapons = $this->em->getRepository(Weapon::class)->findBy(["story" => $story,"starter" => true]);
        $diceType = count($weapons);
        $weaponChosen = Dice::DiceRoller($diceType) - 1;

        $hero->addWeapon($weapons[$weaponChosen]);
    }

    public function starterItem(Story $story, Hero $hero)
    {
        $consumables = $this->em->getRepository(ConsumableItem::class)->findBy(["story" => $story, "starter" => true]);
        $specialItems = $this->em->getRepository(SpecialItem::class)->findBy(["story" => $story, "starter" => true]);

        $lengthConsumables = count($consumables);
        $lengthSpecials = count($specialItems);
        $diceType = $lengthSpecials + $lengthConsumables;
        $diceScore = Dice::DiceRoller($diceType);

        if($diceScore > $lengthConsumables) {
            $itemChosen = Dice::DiceRoller($lengthSpecials) - 1;

            $hero->addSpecialItem($specialItems[$itemChosen]);
        }else {
            $itemChosen = Dice::DiceRoller($lengthConsumables) - 1;
            $backpackItem = new BackpackItem($hero, $consumables[$itemChosen]);
            $this->em->persist($backpackItem);
            $this->em->flush();
        }
    }
}
