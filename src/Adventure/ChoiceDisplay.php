<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 19/12/2017
 * Time: 17:43
 */

namespace App\Adventure;

/**
 * This service will unlock the choices for which the hero satisfies the requirements in gold, item or skill
 */

use App\Entity\Choice;
use App\Entity\Hero;

class ChoiceDisplay
{

    public static function unlockChoices(Hero $hero, Choice $choice) : Choice
    {
        self::goldUnlock($hero, $choice);
        self::skillUnlock($hero, $choice);
        self::itemUnlock($hero, $choice);

        return $choice;
    }

    private static function goldUnlock(Hero $hero, Choice $choice) : void
    {
        $goldRequired = $choice->getGoldRequired();
        $goldHero = $hero->getGold();
        if($goldRequired > 0) {
            $goldRequired > $goldHero
                ? $choice->setLocked(true)
                : $choice->setLocked(false);
        }

    }

    private static function skillUnlock(Hero $hero, Choice $choice) : void
    {
        $skillRequired = $choice->getSkillRequired();
        if(isset($skillRequired)) {
            $hero->hasSkill($skillRequired)
                ? $choice->setLocked(false)
                : $choice->setLocked(true);
        }

    }

    private static function itemUnlock(Hero $hero, Choice $choice) : void
    {
        $itemRequired = $choice->getItemRequired();
        if(isset($itemRequired)) {
            $hero->hasItem($itemRequired)
                ? $choice->setLocked(false)
                : $choice->setLocked(true);
        }
    }
}
