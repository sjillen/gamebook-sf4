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


class ChoiceInteraction
{

    const GOLD = 1;
    const ITEM = 2;
    const LIFE = 3;

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
}