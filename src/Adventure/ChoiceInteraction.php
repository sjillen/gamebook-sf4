<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 20/12/2017
 * Time: 22:31
 */

namespace App\Adventure;


use App\Dice\Dice;
use App\Entity\Choice;
use App\Entity\Hero;


class ChoiceInteraction
{

    const GOLD = 1;
    const ITEM = 2;
    const LIFE = 3;
    const BACKPACK_LOST = 4;
    const WEAPON_LOSS = 5;
    const ALLWEAPONS_LOSS = 6;
    const NO_WEAPON_LOSS = 7;


    public static function trade(Hero $hero, Choice $choice) : ?array
    {
        $trades = [];
        $gold = self::tradeGold($hero, $choice);
        if (isset($gold)) {
            $trades[] = $gold;
        }
        $item = self::tradeItem($hero, $choice);
        if (isset($item)) {
            $trades[] = $item;
        }
        $life = self::tradeLife($hero, $choice);
        if (isset($life)) {
            $trades[] = $life;
        }
        if (count($trades) > 0) {
            return $trades;
        } else {
            return null;
        }
    }

    public static function tradeGold(Hero $hero, Choice $choice)
    {
        $goldRequired = $choice->getGoldRequired();
        if ($goldRequired !== 0) {
            $goldCarried = $hero->getGold();
            $hero->setGold($goldCarried - $goldRequired);
            return self::GOLD;
        }

    }

    public static function tradeLife(Hero $hero, Choice $choice)
    {
        $damages = $choice->getDamages();
        if ($damages !== 0) {
            $life = $hero->getLife();
            $lifeLeft = $life - $damages;
            $lifeLeft <= 0 ? $hero->setLife(0) : $hero->setLife($lifeLeft);
            return self::LIFE;
        }
    }

    public static function tradeItem(Hero $hero, Choice $choice)
    {
        $item = $choice->getItemRequired();
        if (isset($item)) {
            $hero->removeSpecialItem($item);
            return self::ITEM;
        }
    }

    public static function removeBackpack(Hero $hero, Choice $choice) : ?int
    {
        $isBackpackLost = $choice->isBackpackLost();
        if ($isBackpackLost) {
            $hero->clearBackpackItems();
            return self::BACKPACK_LOST;
        } else {
            return null;
        }
    }

    public  function removeWeapon(Hero $hero, Choice $choice) : int
    {
        $weaponLoss = $choice->getWeaponLost();
        $result = self::NO_WEAPON_LOSS;
        $weapons = $hero->getWeapons();
        if (count($weapons) > 0) {
            switch ($weaponLoss) {
                case Choice::NO_LOSS:
                    $result = self::NO_WEAPON_LOSS;
                    break;
                case Choice::WEAPON_LOSS:
                    $diceType = count($weapons);
                    $index = Dice::DiceRoller($diceType) - 1;
                    $weaponRemoved = $weapons[$index];
                    $weaponSkillBonus = Alteration::weaponSkillBonus($hero, $weaponRemoved);
                    if($weaponSkillBonus) {
                        //cancel the bonus given
                        $hero->setAbility($hero->getAbility() - 4);
                    }
                    //Check if hero is still carrying at least one weapon
                    if ($hero->getWeapons()->isEmpty()) {
                        $hero->setAbility($hero->getAbility() - 4);
                    }
                    $hero->removeWeapon($weaponRemoved);
                    $result = self::WEAPON_LOSS;
                    break;
                case Choice::ALLWEAPONS_LOSS:
                    foreach ( $weapons as $weapon) {
                        $weaponSkillBonus = Alteration::weaponSkillBonus($hero, $weapon);
                        if($weaponSkillBonus) {
                            //cancel the bonus given
                            $hero->setAbility($hero->getAbility() - 4);
                        }
                    }
                    $hero->setAbility($hero->getAbility() - 4);
                    $hero->clearWeapons();
                    $result = self::ALLWEAPONS_LOSS;
                    break;
                default:
                    $result = self::NO_WEAPON_LOSS;
                    break;
            }
        }

        return $result;
    }
}