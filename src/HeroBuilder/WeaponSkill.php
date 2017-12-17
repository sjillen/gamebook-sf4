<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 17/12/2017
 * Time: 14:44
 */

namespace App\HeroBuilder;

use App\Dice\Dice;
use App\Entity\Skill;
use App\Entity\Story;
use App\Entity\Hero;
use App\Entity\Weapon;
use App\Repository\WeaponRepository;



class WeaponSkill
{
    private $wr;

    public function __construct(WeaponRepository $wr)
    {
        $this->wr = $wr;
    }

    public function weaponSelection(Story $story, Hero $hero)
    {
        $weapons = $this->wr->findBy(["story" => $story]);
        $diceType = count($weapons);
        $weapon = $weapons[(Dice::DiceRoller($diceType)) - 1];

        $skills = $hero->getSkills();
        if (in_array("Weaponskill", $skills)) {

        }
    }
}
