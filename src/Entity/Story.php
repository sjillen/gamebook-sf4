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
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        return $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        return $this->title = $title;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        return $this->author = $author;
    }

    public function getSaga()
    {
        return $this->saga;
    }

    public function setSaga($saga)
    {
        return $this->saga = $saga;
    }

    public function getChapters()
    {
        return $this->chapters;
    }

    public function addChapters(Chapter $chapter)
    {
        return $this->chapters[] = $chapter;
    }

    public function removeChapter(Chapter $chapter)
    {
        return $this->chapters->removeElement($chapter);
    }

    public function getSkills()
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill)
    {
        return $this->skills[] = $skill;
    }

    public function removeSkill(Skill $skill)
    {
        return $this->skills->removeElement($skill);
    }
}
