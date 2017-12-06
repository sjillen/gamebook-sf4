<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\ItemBase;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WeaponRepository")
 */
class Weapon extends ItemBase
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $weaponSkill;

    /* Setters and Getters */

    public function getId() : int 
    {
        return $this->id;
    }

    public function setId($id) : void 
    {
        $this->id = $id;
    }

    public function getWeaponSkill() : string 
    {
        return $this->weaponSkill;
    }

    public function setWeaponSkill($weaponSkill) : void 
    {
        $this->weaponSkill = $weaponSkill;
    }


}
