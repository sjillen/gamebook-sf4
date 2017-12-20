<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Chapter;
use App\Entity\Skill;
use App\Entity\Item;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChoiceRepository")
 */
class Choice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * proprietary chapter
     * 
     * @ORM\ManyToOne(targetEntity="Chapter", inversedBy="choices", cascade={"persist"})
     */
    private $chapter;

    /**
     * chapter for redirection
     * 
     * @ORM\ManyToOne(targetEntity="Chapter")
     */
    private $targetChapter;

    /**
     * display by default or not
     * 
     * @ORM\Column(type="boolean")
     */
    private $locked;

    /**
     * Skill to unlock to choice
     * 
     * @ORM\ManyToOne(targetEntity="Skill")
     */
    private $skillRequired;

    /**
     * Item to unlock choice
     * 
     * @ORM\ManyToOne(targetEntity="SpecialItem")
     */
    private $itemRequired;

    /**
     * Amount of gold to unlock choice
     * 
     * @ORM\Column(type="integer")
     */
     private $goldRequired;

     /* Constructor */
     public function __construct()
     {
         $this->locked = false;
         $this->chapter = new Chapter();
         $this->targetChapter = new Chapter();
         $this->itemRequired = new SpecialItem();
         $this->goldRequired = 0;
     }

     /* Setters and Getters */
     public function getId() : int
     {
         return $this->id;
     }
     
     /* Setters and Getters */
     public function getDescription() : ?string
     {
         return $this->description;
     }

     public function setDescription($description) : void
     {
         $this->description = $description;
     }

     public function getChapter() : Chapter
     {
         return $this->chapter;
     }

     public function setChapter(Chapter $chapter) : void
     {
             $this->chapter = $chapter;
     }

     public function getTargetChapter() : ?Chapter
     {
         return $this->targetChapter;
     }

     public function setTargetChapter(?Chapter $targetChapter) : void
     {
         $this->targetChapter = $targetChapter;
     }

     public function isLocked() : bool
     {
         return $this->locked;
     }

     public function setLocked($locked) : void
     {
         $this->locked = $locked;
     }

     public function getSkillRequired() : ?Skill
     {
         return $this->skillRequired;
     }

     public function setSkillRequired(Skill $skillRequired) : void
     {
         $this->skillRequired = $skillRequired;
     }

     public function getItemRequired() : ?SpecialItem
     {
         return $this->itemRequired;
     }

     public function setItemRequired(?SpecialItem $itemRequired) : void
     {
         $this->itemRequired = $itemRequired;
     }

     public function getGoldRequired() : ?int
     {
         return $this->goldRequired;
     }

     public function setGoldRequired($goldRequired) : void
     {
         $this->goldRequired = $goldRequired;
     }





    
}
