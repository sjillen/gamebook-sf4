<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SagaRepository")
 */
class Saga
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Story", mappedBy="saga", cascade={"remove"})
     */
    private $stories;

    /**
     * Saga constructor.
     * @param $stories
     */
    public function __construct()
    {
        $this->stories = new ArrayCollection();
    }

    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) : void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getStories()
    {
        return $this->stories;
    }

    /**
     * @param mixed $stories
     */
    public function addStory(?Story $story = null) : void
    {
        $this->stories[] = $story;
    }

    public function removeStory(?Story $story = null) : void
    {
        $this->stories->removeElement($story);
    }
}
