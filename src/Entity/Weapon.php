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
     * @ORM\OneToOne(targetEntity="Skill")
     */
    private $weaponSkill;

    /**
     * @ORM\ManyToOne(targetEntity="Story", inversedBy="weapons")
     */
    protected $story;

    /* Setters and Getters */

    public function getId() : int 
    {
        return $this->id;
    }

    public function setId($id) : void 
    {
        $this->id = $id;
    }

    public function getWeaponSkill() : ?Skill
    {
        return $this->weaponSkill;
    }

    public function setWeaponSkill(Skill $weaponSkill) : void
    {
        $this->weaponSkill = $weaponSkill;
    }
    public function getStory() : ?Story
    {
        return $this->story;
    }

    public function setStory(?Story $story)
    {
        return $this->story = $story;
    }
}
