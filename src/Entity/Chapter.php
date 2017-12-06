<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Choice;
use App\Entity\Story;

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
     */
    private $story;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $textContent;

    /**
     * @ORM\ManyToMany(targetEntity="Npc")
     */
    private $npcs;

    /**
     * @ORM\OneToMany(targetEntity="Choice", mappedBy="chapter")
     */
    private $choices;

    /* Constructor */
    public function __constructor()
    {
        $this->choices = new ArrayCollection();
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

    public function getStory()
    {
        return $this->story;
    }

    public function setStory($story)
    {
        return $this->story = $story;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        return $this->title = $title;
    }

    public function getTextContent()
    {
        return $this->textContent;
    }

    public function setTextContent($textContent)
    {
        return $this->textContent = $textContent;
    }

    public function getNpcs() : array 
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

    public function getChoices() 
    {
        return $this->choices;
    }

    public function addChoice(Choice $choice)
    {
        return $this->choices[] = $choice;
    }

    public function removeChoice(Choice $choice)
    {
        return $this->choices->removeElement($choice);
    }
}
