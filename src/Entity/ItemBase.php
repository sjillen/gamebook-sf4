<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $starter;

    /**
     * How much it increase or decrease a hero attribute
     *
     * @ORM\Column(type="integer")
     */
    private $bonusGiven;

    /**
     * Which attribute will be affected
     *
     * @ORM\Column(type="string")
     * Life or Energy
     */
    private $attributeTargeted;

    /* Setters and Getters */

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

    public function getStarter() : ?bool
    {
        return $this->starter;
    }

    public function setStarter($starter) : void
    {
        $this->starter = $starter;
    }

    public function getBonusGiven() : ?int
    {
        return $this->bonusGiven;
    }

    public function setBonusGiven($bonus) : void
    {
        $this->bonusGiven = $bonus;
    }

    public function getAttributeTargeted() : ?string
    {
        return $this->attributeTargeted;
    }

    public function setAttributeTargeted($attributeTargeted) : void
    {
        $this->attributeTargeted = $attributeTargeted;
    }
}
