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
use App\Entity\Ruleset;
use App\Entity\Story;
use App\Entity\Hero;
use App\Entity\Weaponskill;
use App\Repository\WeaponRepository;
use App\Repository\SkillRepository;



class HeroSkills
{
    private $wr;
    private $sr;

    public function __construct(WeaponRepository $wr, SkillRepository $sr)
    {
        $this->wr = $wr;
        $this->sr = $sr;
    }

    public function weaponSkillSelection(Story $story, Hero $hero)
    {
        $skill = $this->sr->findOneBy(["story" => $story, "name" => "Weaponskill"]);
        $IsWeaponSkill = $hero->hasSkill($skill);

        if($IsWeaponSkill) {
            $weapons = $this->wr->findBy(["story" => $story]);
            $diceType = count($weapons);
            $weapon = $weapons[(Dice::DiceRoller($diceType)) - 1];
            $weaponskill = new Weaponskill();
            $weaponskill->setName("Weaponskill: " . $weapon->getName());
            $weaponskill->setDescription("Mastery of the following weapon: " . $weapon->getName());
            $weaponskill->setWeaponMastered($weapon);
            $weaponskill->setStory($story);
            $hero->removeSkill($skill);
            $hero->setWeaponskill($weaponskill);
        }
    }

    public static function maxSkillAllowed(Ruleset $ruleset, Hero $hero)
    {
        return count($hero->getSkills()) != $ruleset->getMaxSkill();

    }
}
