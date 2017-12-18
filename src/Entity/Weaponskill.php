<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Skill;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WeaponskillRepository")
 */
class Weaponskill extends Skill
{
    /**
     * @ORM\ManyToOne(targetEntity="Weapon")
     */
    private $weaponMastered;

    public function getWeaponMastered() : ?Weapon
    {
        return $this->weaponMastered;
    }

    public function setWeaponMastered(?Weapon $weapon) : void
    {
        $this->weaponMastered = $weapon;
    }
}
