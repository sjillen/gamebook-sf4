<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RulesetRepository")
 */
class Ruleset
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Story", inversedBy="ruleset")
     */
    private $story;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxSkill;

    /**
     * @ORM\Column(type="integer")
     */
    private $heroBaseLife;

    /**
     * @ORM\Column(type="integer")
     */
    private $heroBaseResource;

    /**
     * @ORM\Column(type="integer")
     */
    private $heroBaseGold;

    /**
     * @ORM\Column(type="integer")
     */
    private $diceType;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getStory()
    {
        return $this->story;
    }

    /**
     * @param mixed $story
     */
    public function setStory($story)
    {
        $this->story = $story;
    }

    /**
     * @return mixed
     */
    public function getMaxSkill()
    {
        return $this->maxSkill;
    }

    /**
     * @param mixed $maxSkill
     */
    public function setMaxSkill($maxSkill)
    {
        $this->maxSkill = $maxSkill;
    }

    /**
     * @return mixed
     */
    public function getHeroBaseLife()
    {
        return $this->heroBaseLife;
    }

    /**
     * @param mixed $heroBaseLife
     */
    public function setHeroBaseLife($heroBaseLife)
    {
        $this->heroBaseLife = $heroBaseLife;
    }

    /**
     * @return mixed
     */
    public function getHeroBaseResource()
    {
        return $this->heroBaseResource;
    }

    /**
     * @param mixed $heroBaseResource
     */
    public function setHeroBaseResource($heroBaseResource)
    {
        $this->heroBaseResource = $heroBaseResource;
    }

    /**
     * @return mixed
     */
    public function getHeroBaseGold()
    {
        return $this->heroBaseGold;
    }

    /**
     * @param mixed $heroBaseGold
     */
    public function setHeroBaseGold($heroBaseGold)
    {
        $this->heroBaseGold = $heroBaseGold;
    }

    /**
     * @return mixed
     */
    public function getDiceType()
    {
        return $this->diceType;
    }

    /**
     * @param mixed $diceType
     */
    public function setDiceType($diceType)
    {
        $this->diceType = $diceType;
    }


}
