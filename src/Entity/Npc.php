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
     * skill which affect the NPC
     * 
     * @ORM\ManyToOne(targetEntity="Skill")
     */
    private $skillAffect;

    /* Setters and Getters */

    public function getId() : int 
    {
        return $this->id;
    }

    public function getSkillAffect() : Skill
    {
        return $this->skill;
    }

    public function setSkillAffect(Skill $skill) : void 
    {
        $this->skill = $skill;
    }
}
