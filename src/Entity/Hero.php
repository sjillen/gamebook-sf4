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
    const IS_DEAD = 0;
    const ON_ADVENTURE = 1;
    const AT_TAVERN = 2;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="heroes", cascade={"persist"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Story")
     */
    private $story;

    /**
     * @ORM\ManyToOne(targetEntity="Chapter")
     */
    private $chapter;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxLife;

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

    /**
     * @ORM\Column(type="integer")
     */
    private $chapterIterator;

    /**
     * @ORM\Column(type="integer")
     * 0 : ISDEAD
     * 1 : ONADVENTURE
     * 2 : ATTAVERN
     */
    private $status;

    /**
     * Hero constructor.
     */
    public function __construct()
    {
        $this->user = new User();
        $this->skills = new ArrayCollection();
        $this->weapons = new ArrayCollection();
        $this->backpackItems = new ArrayCollection();
        $this->specialItems = new ArrayCollection();
        $this->level = 1;
        $this->gold = 0;
        $this->numberOfMeals = 0;
        $this->chapterIterator = 1;
        $this->status = self::ON_ADVENTURE;
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

    public function getUser() : ?User
    {
        return $this->user;
    }

    public function setUser(?User $user) : void
    {
        $this->user = $user;
    }

    public function getStory() : ?Story
    {
        return $this->story;
    }

    public function setStory(?Story $story) : void
    {
        $this->story = $story;
    }

    public function getChapter() : ?Chapter
    {
        return $this->chapter;
    }

    public function setChapter(?Chapter $chapter) : void
    {
        $this->chapter = $chapter;
    }

    public function getLevel() : int
    {
        return $this->level;
    }

    public function setLevel(?int $level) : void
    {
        $this->level = $level;
    }

    public function setMaxLife(?int $life) : void
    {
        $this->maxLife = $life;
    }

    public function getMaxLife() : ?int
    {
        return $this->maxLife;
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

    public function getChapterIterator() : ?int
    {
        return $this->chapterIterator;
    }

    public function setChapterIterator(?int $iterator) : void
    {
        $this->chapterIterator = $iterator;
    }

    public function iterate() : void
    {
        $this->chapterIterator += 1;
    }

    public function iteratorReset() : void
    {
        $this->chapterIterator = 0;
    }

    public function getStatus() : ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status) : void
    {
        $this->status = $status;
    }
}
