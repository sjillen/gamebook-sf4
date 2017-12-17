<?php

namespace App\Entity;

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
     * @ORM\ManyToMany(targetEntity="Weapon")
     */
    private $weapons;

    /**
     * @ORM\ManyToMany(targetEntity="ConsumableItem")
     */
    private $consumableItems;

    /**
     * @ORM\ManyToMany(targetEntity="SpecialItem")
     */
    private $specialItems;

    /* Constructor */
    public function __construct()
    {
        $this->skills = new ArrayCollection();
        $this->weapons = new ArrayCollection();
        $this->consumableItems = new ArrayCollection();
        $this->specialItems = new ArrayCollection();
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

    public function getGold() : int
    {
        return $this->gold;
    }

    public function setGold($gold) : void
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

    public function getSkills() : ?ArrayCollection
    {
        return $this->skills;
    }

    public function hasSkill(Skill $skill) : ?bool
    {
           return $this->skills->contains($skill);
    }

    public function addSkill(Skill $skill) : void
    {
        $this->skills[] = $skill;
    }

    public function removeSkill(Skill $skill) : void
    {
        $this->skills->removeElement($skill);
    }

    public function getWeapons() : ?ArrayCollection
    {
        return $this->weapons;
    }

    public function addWeapon(Weapon $weapon) : void
    {
        $this->weapons[] = $weapon;
    }

    public function removeWeapon(Weapon $weapon) : void 
    {
        $this->weapons->removeElement($weapon);
    }

    public function getConsumableItems() : ?ArrayCollection
    {
        return $this->consumableItems;
    }

    public function addConsumableItem(ConsumableItem $consumableItem) : void 
    {
        $this->consumableItems[] = $consumableItem;
    }

    public function removeConsumableItem(ConsumableItem $consumableItem) : void 
    {
        $this->consumableItems->removeElement($consumableItem);
    }

    public function getSpecialItems() : ?ArrayCollection
    {
        return $this->specialItems;
    }

    public function addSpecialItem(SpecialItem $specialItem) : void 
    {
        $this->specialItems[] = $specialItem;
    }

    public function removeSpecialItem(SpecialItem $specialItem) : void 
    {
        $this->specialItems->removeElement($specialItem);
    }
}
