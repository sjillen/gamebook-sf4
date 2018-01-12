<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ChapterRepository")
 */
class Chapter
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Story", inversedBy="chapters")
     *
     */
    private $story;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * Type of chapter:
     * Starter
     * Standard
     * Death
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="text")
     */
    private $textContent1;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $textContent2;

    /**
     * @ORM\ManyToMany(targetEntity="Npc")
     *
     */
    private $npcs;

    /**
     * @ORM\OneToMany(targetEntity="Choice", mappedBy="chapter", cascade={"persist", "remove"})
     *
     */
    private $choices;

    /**
     * @ORM\ManyToMany(targetEntity="Weapon")
     *
     */
    private $weapons;

    /**
     * @ORM\ManyToMany(targetEntity="SpecialItem")
     *
     */
    private $specialItems;

    /**
     * @ORM\ManyToMany(targetEntity="ConsumableItem")
     *
     */
    private $consumableItems;

    /**
     * @ORM\Column(type="integer")
     */
    private $gold;

    /* Constructor */
    public function __construct()
    {
        $this->choices = new ArrayCollection();
        $this->npcs = new ArrayCollection();
        $this->story = new Story();
        $this->type = "standard";
        $this->specialItems = new ArrayCollection();
        $this->consumableItems = new ArrayCollection();
        $this->weapons = new ArrayCollection();
        $this->gold = 0;
    }

    /* Setters and Getters */

    public function getId() : int
    {
        return $this->id;
    }

    public function getStory() : ?Story
    {
        return $this->story;
    }

    public function setStory($story) : void
    {
        $this->story = $story;
    }

    public function getTitle() : ?string
    {
        return $this->title;
    }

    public function setTitle($title) : void
    {
        $this->title = $title;
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function setType($type) : void
    {
        $this->type = $type;
    }

    public function getTextContent1()
    {
        return $this->textContent1;
    }

    public function setTextContent1($textContent) : void
    {
        $this->textContent1 = $textContent;
    }

    public function getTextContent2()
    {
        return $this->textContent2;
    }

    public function setTextContent2($textContent) : void
    {
        $this->textContent2 = $textContent;
    }

    public function getNpcs() : ?Collection
    {
        return $this->npcs;
    }

    public function addNpc(Npc $npc) : void 
    {
        $this->npcs[] = $npc;
    }

    public function removeNpc(Npc $npc) : void 
    {
        $this->npcs->removeElement($npc);
    }

    public function getChoices() : ?Collection
    {
        return $this->choices;
    }

    public function addChoice(Choice $choice) : void
    {
        $choice->setChapter($this);
        $this->choices[] = $choice;
    }

    public function removeChoice(Choice $choice) : void
    {
        $this->choices->removeElement($choice);
    }

    public function getSpecialItems() : ?Collection
    {
        return $this->specialItems;
    }

    public function addSpecialItem(?SpecialItem $item) : void
    {
        $this->specialItems[] = $item;
    }

    public function hasSpecialItem(?SpecialItem $item) : bool
    {
        return $this->specialItems->contains($item);
    }

    public function removeSpecialItem(?SpecialItem $item) : void
    {
        $this->specialItems->removeElement($item);
    }

    public function getConsumableItems() : ?Collection
    {
        return $this->consumableItems;
    }

    public function setConsumableItems(?Collection $consumableItems) : void
    {
        $this->consumableItems = $consumableItems;
    }

    public function addConsumableItem(?ConsumableItem $item) : void
    {
        $this->consumableItems[] = $item;
    }

    public function removeConsumableItem(?ConsumableItem $item) : void
    {
        $this->consumableItems->removeElement($item);
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

    public function hasWeapon(Weapon $weapon): ?bool
    {
        return $this->weapons->contains($weapon);
    }

    public function getGold() : ?int
    {
        return $this->gold;
    }

    public function setGold(?int $gold) : void
    {
        $this->gold = $gold;
    }
}
