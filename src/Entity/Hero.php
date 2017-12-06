<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\CharacterBase;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Skill;
use App\Entity\SpecialItem;
use App\Entity\ConsumableItem;

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
    private $ConsumableItems;

    /**
     * @ORM\ManyToMany(targetEntity="SpecialItem")
     */
    private $specialItems;

    /* Constructor */
    public function __constructor()
    {
        $this->skills = new ArrayCollection;
        $this->weapons = new ArrayCollection;
        $this->consumableItems = new ArrayCollection;
        $this->specialItems = new ArrayCollection;
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

    public function getSkill() : array
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill) : void
    {
        $this->skills[] = $skill;
    }

    public function removeSkill(Skill $skill) : void
    {
        $this->skills->removeElement($skill);
    }

    public function getWeapons() : array
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

    public function getConsumableItems() : array
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

    public function getSpecialItems() : array
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
