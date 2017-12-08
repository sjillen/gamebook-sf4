<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Story;

/**
 * @ORM\MappedSuperclass
 */
Abstract class CharacterBase
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * Endurance equivalent for lone wolf
     * 
     * @ORM\Column(type="integer")
     */
    protected $life;

    /**
     * Combat skill equivalent for lone wolf
     * 
     * @ORM\Column(type="integer")
     */
    protected $energy;

    /**
     * @ORM\ManyToOne(targetEntity="Story")
     */
    protected $story;

    /* Constructor */
    public function __construct()
    {
        $this->story = new Story();
    }

    /* Setters and Getters */

    public function getName() : ?string
    {
        return $this->name;
    }

    public function setName($name) : void
    {
        $this->name = $name;
    }

    public function getLife() : ?int
    {
        return $this->life;
    }

    public function setLife($life) : void
    {
        $this->life = $life;
    }

    public function getEnergy() : ?int
    {
        return $this->energy;
    }

    public function setEnergy($energy) : void
    {
        $this->energy = $energy;
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
