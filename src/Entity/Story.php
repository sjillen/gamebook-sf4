<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Chapter;
use App\Entity\Skill;

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
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $saga;

    /**
     * @ORM\OneToMany(targetEntity="Chapter", mappedBy="story", cascade={"remove"})
     */
    private $chapters;

    /**
     * @ORM\OneToMany(targetEntity="Skill", mappedBy="story", cascade={"remove"})
     */
    private $skills;

    //Constructor
    public function __construct()
    {
        $this->skills = new ArrayCollection();
        $this->chapters = new arrayCollection();
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

    public function getSaga() : ?string
    {
        return $this->saga;
    }

    public function setSaga($saga) : void
    {
        $this->saga = $saga;
    }

    public function getChapters()
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

    public function getSkills()
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
}
