<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Story;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SkillRepository")
 */
class Skill
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
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="story", inversedBy="skills")
     */
    private $story;

    /* Getters and Setters */

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

    public function setStory($story) : void
    {
        $this->story = $story;
    }

    public function getUniqueName() : string
    {
        return sprintf('%s - %s', $this->name, $this->description);
    }
}
