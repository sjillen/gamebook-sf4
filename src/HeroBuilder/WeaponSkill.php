<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 17/12/2017
 * Time: 14:44
 */

namespace App\HeroBuilder;


/* This service will check if Hero has Skill Weaponskill and set a Weapon for it */
use App\Dice\Dice;
use App\Entity\Story;
use App\Entity\Hero;
use App\Repository\WeaponRepository;
use App\Repository\SkillRepository;



class WeaponSkill
{
    private $wr;
    private $sr;

    public function __construct(WeaponRepository $wr, SkillRepository $sr)
    {
        $this->wr = $wr;
        $this->sr = $sr;
    }

    public function weaponSelection(Story $story, Hero $hero)
    {
        $weapons = $this->wr->findBy(["story" => $story]);
        $skill = $this->sr->findOneBy(["story" => $story, "name" => "Weaponskill"]);
        $diceType = count($weapons);
        $weapon = $weapons[(Dice::DiceRoller($diceType)) - 1];
        $IsWeaponSkill = $hero->hasSkill($skill);

        if($IsWeaponSkill) {
            $hero->removeSkill($skill);
            $skill->setWeapon($weapon);
            $hero->addSkill($skill);
        }
    }
}
