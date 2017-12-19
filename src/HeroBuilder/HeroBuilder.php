<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 16/12/2017
 * Time: 00:23
 */

namespace App\HeroBuilder;

use App\Entity\Hero;
use App\Dice\Dice;
use App\Entity\Ruleset;
use App\Repository\RulesetRepository;


class HeroBuilder
{
    public function buildHero(Hero $hero, Ruleset $rules) : Hero
    {
        $hero->setGold(self::goldStarter($rules));
        $hero->setLife(self::setHpStarter($rules));
        $hero->setEnergy(self::setEnergyStarter($rules));

        return $hero;
    }

    private static function goldStarter(Ruleset $rules) : int
    {
        $gold = Dice::DiceRoller($rules->getDiceType()) + $rules->getHeroBaseGold();
        return $gold;
    }

    private static function setHpStarter(Ruleset $rules) : int
    {
        $hp = Dice::DiceRoller($rules->getDiceType()) + $rules->getHeroBaseLife();
        return $hp;
    }

    private static function setEnergyStarter(Ruleset $rules) : int
    {
        $energy = Dice::DiceRoller($rules->getDiceType()) + $rules->getHeroBaseResource();
        return $energy;
    }
}
