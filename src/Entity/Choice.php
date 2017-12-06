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
     * @ORM\ManyToOne(targetEntity="Chapter", inversedBy="choices")
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
     }

     /* Setters and Getters */
     public function getId()
     {
         return $this->id;
     }
 
     public function setId($id)
     {
         return $this->id = $id;
     }
     
     /* Setters and Getters */
     public function getDescription()
     {
         return $this->description;
     }

     public function setDescription($description)
     {
         return $this->description = $description;
     }

     public function getChapter()
     {
         return $this->chapter;
     }

     public function setChapter(Chapter $chapter)
     {
         return $this->chapter = $chapter;
     }

     public function getTargetChapter()
     {
         return $this->targetChapter;
     }

     public function setTargetChapter(Chapter $targetChapter)
     {
         return $this->targetChapter = $targetChapter;
     }

     public function getLocked()
     {
         return $this->locked;
     }

     public function setLocked($locked)
     {
         return $this->locked = $locked;
     }

     public function getSkillRequired()
     {
         return $this->skillRequired;
     }

     public function setSkillRequired(Skill $skillRequired)
     {
         return $this->skillRequired = $skillRequired;
     }

     public function getItemRequired()
     {
         return $this->itemRequired;
     }

     public function setItemRequired(Item $itemRequired)
     {
         return $this->itemRequired = $itemRequired;
     }

     public function getGoldRequired()
     {
         return $this->goldRequired;
     }

     public function setGoldRequired($goldRequired)
     {
         return $this->goldRequired = $goldRequired;
     }





    
}
