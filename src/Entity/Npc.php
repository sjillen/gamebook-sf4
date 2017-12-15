<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\CharacterBase;
use App\Entity\Skill;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NpcRepository")
 */
class Npc extends CharacterBase
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Short description of the character or monster
     * 
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * skill which affect the NPC
     * 
     * @ORM\ManyToOne(targetEntity="Skill")
     */
    private $skillAffect;

    /**
     * @ORM\ManyToOne(targetEntity="Story", inversedBy="npcs")
     */
    protected $story;

    /* Setters and Getters */

    public function getId() : int 
    {
        return $this->id;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description) : void 
    {
        $this->description = $description;
    }

    public function getSkillAffect()
    {
        return $this->skillAffect;
    }

    public function setSkillAffect(Skill $skill) : void 
    {
        $this->skillAffect = $skill;
    }

    public function getStory() : ?Story
    {
        return $this->story;
    }

    public function setStory(Story $story) : void
    {
        $this->story = $story;
    }
}
