<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity(repositoryClass="App\Repository\HeroRepository")
 */
class Hero extends CharacterBase
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Story")
     */
    private $story;

    /**
     * @ORM\Column(type="integer")
     */
    private $gold;

    /**
     * @ORM\Column(type="integer")
     */
    private $level;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberOfMeals;

    /**
     * @ORM\ManyToMany(targetEntity="Skill")
     */
    private $skills;

    /**
     * @ORM\ManyToOne(targetEntity="Weaponskill", cascade={"persist", "remove"})
     */
    private $weaponskill;

    /**
     * @ORM\ManyToMany(targetEntity="Weapon")
     */
    private $weapons;

    /**
     * @ORM\OneToMany(targetEntity="BackpackItem", mappedBy="hero", cascade={"remove"})
     */
    private $backpackItems;

    /**
     * @ORM\ManyToMany(targetEntity="SpecialItem")
     */
    private $specialItems;

    /* Constructor */
    public function __construct()
    {
        $this->skills = new ArrayCollection();
        $this->weapons = new ArrayCollection();
        $this->backpackItems = new ArrayCollection();
        $this->specialItems = new ArrayCollection();
        $this->level = 1;
        $this->gold = 0;
        $this->numberOfMeals = 0;
    }

    /* Setters and Getters */

    public function getId() : int
    {
        return $this->id;
    }

    public function setId($id) : void
    {
        $this->id = $id;
    }

    public function getStory() : ?Story
    {
        return $this->story;
    }

    public function setStory(?Story $story) : void
    {
        $this->story = $story;
    }

    public function getLevel() : int
    {
        return $this->level;
    }

    public function setLevel(?int $level) : void
    {
        $this->level = $level;
    }

    public function getGold() : int
    {
        return $this->gold;
    }

    public function setGold(?int $gold) : void
    {
        $this->gold = $gold;
    }

    public function getNumberOfMeals() : int
    {
        return $this->numberOfMeals;
    }

    public function setNumberOfMeals($meal) : void
    {
        $this->numberOfMeals = $meal;
    }

    public function getSkills()
    {
        return $this->skills;
    }

    public function hasSkill(?Skill $skill) : ?bool
    {
        return $this->skills->contains($skill);
    }

    public function addSkill(?Skill $skill) : void
    {
        $this->skills[] = $skill;
    }

    public function removeSkill(?Skill $skill) : void
    {
        $this->skills->removeElement($skill);
    }

    public function getWeaponskill() : ?Weaponskill
    {
        return $this->weaponskill;
    }

    public function setWeaponskill(?Weaponskill $weaponskill) : void
    {
        $this->weaponskill = $weaponskill;
    }

    public function getWeapons() : ?Collection
    {
        return $this->weapons;
    }

    public function addWeapon(?Weapon $weapon) : void
    {
        $this->weapons[] = $weapon;
    }

    public function removeWeapon(?Weapon $weapon) : void
    {
        $this->weapons->removeElement($weapon);
    }

    public function hasWeapon(?Weapon $weapon) : bool
    {
        return $this->weapons->contains($weapon);
    }

    public function getBackpackItems() : ?Collection
    {
        return $this->backpackItems;
    }

    public function hasBackpackItem(?BackpackItem $backpackItem) : ?bool
    {
        return $this->backpackItems->contains($backpackItem);
    }

    public function addBackpackItem(?BackpackItem $backpackItem) : void
    {
        $this->backpackItems[] = $backpackItem;
    }

    public function removeBackpackItem(?BackpackItem $backpackItem) : void
    {
        $this->backpackItems->removeElement($backpackItem);
    }

    public function getSpecialItems() : ?Collection
    {
        return $this->specialItems;
    }

    public function hasSpecialItem(?SpecialItem $item) : bool
    {
        return $this->specialItems->contains($item);
    }

    public function addSpecialItem(?SpecialItem $specialItem) : void
    {
        $this->specialItems[] = $specialItem;
    }

    public function removeSpecialItem(?SpecialItem $specialItem) : void
    {
        $this->specialItems->removeElement($specialItem);
    }
}
