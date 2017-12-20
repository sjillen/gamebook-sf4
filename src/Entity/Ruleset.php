<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RulesetRepository")
 */
class Ruleset
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Story", inversedBy="ruleset")
     */
    private $story;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxSkill;

    /**
     * @ORM\Column(type="integer")
     */
    private $heroBaseLife;

    /**
     * @ORM\Column(type="integer")
     */
    private $heroBaseResource;

    /**
     * @ORM\Column(type="integer")
     */
    private $heroBaseGold;

    /**
     * @ORM\Column(type="integer")
     */
    private $diceType;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxWeaponCarried;

    /**
     * @ORM\Column(type="integer")
     */
    private $bagpackCapacity;

    /**
     * @return mixed
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) : void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getStory() : Story
    {
        return $this->story;
    }

    /**
     * @param mixed $story
     */
    public function setStory(?Story $story) : void
    {
        $this->story = $story;
    }

    /**
     * @return mixed
     */
    public function getMaxSkill() : ?int
    {
        return $this->maxSkill;
    }

    /**
     * @param mixed $maxSkill
     */
    public function setMaxSkill(?int $maxSkill) : void
    {
        $this->maxSkill = $maxSkill;
    }

    /**
     * @return mixed
     */
    public function getHeroBaseLife() : ?int
    {
        return $this->heroBaseLife;
    }

    /**
     * @param mixed $heroBaseLife
     */
    public function setHeroBaseLife(?int $heroBaseLife) : void
    {
        $this->heroBaseLife = $heroBaseLife;
    }

    /**
     * @return mixed
     */
    public function getHeroBaseResource() : ?int
    {
        return $this->heroBaseResource;
    }

    /**
     * @param mixed $heroBaseResource
     */
    public function setHeroBaseResource(?int $heroBaseResource) : void
    {
        $this->heroBaseResource = $heroBaseResource;
    }

    /**
     * @return mixed
     */
    public function getHeroBaseGold() : ?int
    {
        return $this->heroBaseGold;
    }

    /**
     * @param mixed $heroBaseGold
     */
    public function setHeroBaseGold(?int $heroBaseGold) : void
    {
        $this->heroBaseGold = $heroBaseGold;
    }

    /**
     * @return mixed
     */
    public function getDiceType() : ?int
    {
        return $this->diceType;
    }

    /**
     * @param mixed $diceType
     */
    public function setDiceType(?int $diceType) : void
    {
        $this->diceType = $diceType;
    }

    /**
     * @return mixed
     */
    public function getMaxWeaponCarried() : ?int
    {
        return $this->maxWeaponCarried;
    }

    /**
     * @param mixed $maxWeaponCarried
     */
    public function setMaxWeaponCarried(?int $maxWeaponCarried) : void
    {
        $this->maxWeaponCarried = $maxWeaponCarried;
    }

    /**
     * @return mixed
     */
    public function getBagpackCapacity() : ?int
    {
        return $this->bagpackCapacity;
    }

    /**
     * @param mixed $bagpackCapacity
     */
    public function setBagpackCapacity(?int $bagpackCapacity) : void
    {
        $this->bagpackCapacity = $bagpackCapacity;
    }
}
