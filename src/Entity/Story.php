<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StoryRepository")
 */
class Story
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="stories")
     */
    private $user;

    /**
     *
     * @ORM\Column(type="text")
     */
    private $summary;

    /**
     * @var Saga
     *
     * @ORM\ManyToOne(targetEntity="Saga", inversedBy="stories", cascade={"persist"})
     */
    private $saga;

    /**
     * @ORM\OneToOne(targetEntity="Ruleset", mappedBy="story", cascade={"remove"})
     */
    private $ruleset;

    /**
     * @var Chapter[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Chapter", mappedBy="story", cascade={"remove"})
     */
    private $chapters;

    /**
     * @var Skill[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Skill", mappedBy="story", cascade={"remove"})
     */
    private $skills;

    /**
     * @var Npc[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Npc", mappedBy="story", cascade={"remove"})
     */
    private $npcs;

    /**
     * @var Weapon[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Weapon", mappedBy="story", cascade={"remove"})
     */
    private $weapons;

    /**
     * @var SpecialItem[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="SpecialItem", mappedBy="story", cascade={"remove"})
     */
    private $specialItems;

    /**
     * @var ConsumableItem[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ConsumableItem", mappedBy="story", cascade={"remove"})
     */
    private $consumableItems;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;

    /**
     * Story constructor.
     */
    public function __construct()
    {
        $this->skills = new ArrayCollection();
        $this->chapters = new ArrayCollection();
        $this->npcs = new ArrayCollection();
        $this->weapons = new ArrayCollection();
        $this->consumableItems = new ArrayCollection();
        $this->specialItems = new ArrayCollection();
    }

    /* Getters and Setters */
    
    public function getId() : int
    {
        return $this->id;
    }

    public function getTitle() : ?string
    {
        return $this->title;
    }

    public function setTitle($title) : void
    {
        $this->title = $title;
    }

    public function getAuthor() : ?string
    {
        return $this->author;
    }

    public function setAuthor($author) : void
    {
        $this->author = $author;
    }

    public function getUser() : ?User
    {
        return $this->user;
    }

    public function setUser(?User $user) : void
    {
        $this->user = $user;
    }

    public function getSummary()
    {
        return $this->summary;
    }

    public function setSummary($summary) : void
    {
        $this->summary = $summary;
    }

    public function getSaga() : ?string
    {
        return $this->saga;
    }

    public function setSaga($saga) : void
    {
        $this->saga = $saga;
    }

    /**
     * @return mixed
     */
    public function getRuleset()
    {
        return $this->ruleset;
    }

    /**
     * @param mixed $ruleset
     */
    public function setRuleset($ruleset)
    {
        $this->ruleset = $ruleset;
    }

    public function getChapters() : Collection
    {
        return $this->chapters;
    }

    public function addChapters(Chapter $chapter = null) : void
    {
        $this->chapters[] = $chapter;
    }

    public function removeChapter(Chapter $chapter = null) : void
    {
        $this->chapters->removeElement($chapter);
    }

    public function getSkills() : Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill = null) : void
    {
        $this->skills[] = $skill;
    }

    public function removeSkill(Skill $skill = null) : void
    {
        $this->skills->removeElement($skill);
    }

    public function getNpcs() : Collection
    {
        return $this->npcs;
    }

    public function addNpc(Npc $npc = null) : void
    {
        $this->npcs[] = $npc;
    }

    public function removeNpc(Npc $npc = null) : void
    {
        $this->npcs->removeElement($npc);
    }

    public function getWeapons() : Collection
    {
        return $this->weapons;
    }

    public function addWeapon(?Weapon $weapon = null) : void
    {
        $this->weapons[] = $weapon;
    }

    public function removeWeapon(?Weapon $weapon = null) : void
    {
        $this->weapons->removeElement($weapon);
    }

    public function getConsumableItems() : Collection
    {
        return $this->consumableItems;
    }

    public function addConsumableItem(?ConsumableItem $consumableItem = null) : void
    {
        $this->consumableItems[] = $consumableItem;
    }

    public function removeConsumableItem(?ConsumableItem $consumableItem = null) : void
    {
        $this->consumableItems->removeElement($consumableItem);
    }

    public function getSpecialItems() : Collection
    {
        return $this->specialItems;
    }

    public function addSpecialItem(?SpecialItem $specialItem = null) : void
    {
        $this->specialItems[] = $specialItem;
    }

    public function removeSpecialItem(?SpecialItem $specialItem = null) : void
    {
        $this->specialItems->removeElement($specialItem);
    }

    public function getSlug() : ?string
    {
        return $this->slug;
    }

    public function setSlug($slug) : void
    {
        $this->slug = $slug;
    }

    public function getIsPublished() : ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(?bool $isPublished) : void
    {
        $this->isPublished = $isPublished;
    }
}