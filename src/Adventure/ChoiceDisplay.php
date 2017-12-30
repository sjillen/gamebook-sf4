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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ChoiceDisplay
{

    public static function unlockChoices(Hero $hero, Collection $choices) : Collection
    {
       $unlocked = new ArrayCollection();
        foreach ($choices as $choice) {
            self::unlockChoice($hero, $choice);
            if (!$choice->isLocked()) {
                $unlocked[] = $choice;
            }
        }
        return $unlocked;
    }

    private static function unlockChoice(Hero $hero, Choice $choice) : Choice
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
            $hero->hasSpecialItem($itemRequired)
                ? $choice->setLocked(false)
                : $choice->setLocked(true);
        }
    }
}
