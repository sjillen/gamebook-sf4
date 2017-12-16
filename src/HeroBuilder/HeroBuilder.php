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


class HeroBuilder
{
    private $baseLife;
    private $baseEnergy;
    private $diceType;

    public function __construct()
    {
        $this->baseLife = 20;
        $this->baseEnergy = 10;
        $this->diceType = 10;
    }

    public function buildHero(Hero $hero) : Hero
    {
        $hero->setGold($this->goldStarter());
        $hero->setLife($this->setHpStarter());
        $hero->setEnergy($this->setEnergyStarter());

        return $hero;
    }

    public function goldStarter() : int
    {
        $gold = Dice::DiceRoller($this->diceType) - 1 ;
        return $gold;
    }

    public function setHpStarter() : int
    {
        $hp = Dice::DiceRoller($this->diceType) + 19;
        return $hp;
    }

    public function setEnergyStarter() : int
    {
        $energy = Dice::DiceRoller($this->diceType) + 9;
        return $energy;
    }
}
