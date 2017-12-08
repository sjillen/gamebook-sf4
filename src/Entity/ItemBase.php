<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Story;

/**
 * @ORM\MappedSuperclass
 */
Abstract class ItemBase
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="Story")
     */
    protected $story;

    /* Constructor */
    public function construct()
    {
        $this->story = new Story();
    }

    /* Setters and Getters*/

    public function getId() : int
    {
        return $this->id;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function setName($name) : void
    {
        $this->name = $name;
    }

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function setDescription($description) : void
    {
        $this->description = $description;
    }

    public function getStory() : Story
    {
        return $this->story;
    }

    public function setStory(Story $story)
    {
        return $this->story = $story;
    }
}
