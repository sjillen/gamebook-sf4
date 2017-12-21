<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BackpackItemRepository")
 */
class BackpackItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Hero", inversedBy="backpackItems", cascade={"persist"})
     */
    private $hero;

    /**
     * @ORM\ManyToOne(targetEntity="ConsumableItem")
     */
    private $item;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * Bagpack constructor.
     */
    public function __construct(Hero $hero, ConsumableItem $item, int $stock)
    {
        $this->hero = $hero;
        $this->item = $item;
        $this->stock = $stock;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getHero() : ?Hero
    {
        return $this->hero;
    }

    public function setHero(Hero $hero) : void
    {
        $this->hero = $hero;
    }

    public function getItem() : ?ConsumableItem
    {
        return $this->item;
    }

    public function setItem(?ConsumableItem $item) : void
    {
        $this->item = $item;
    }

    public function getStock() : ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock) : void
    {
        $this->stock = $stock;
    }

    public function addStock(?int $stock) : void
    {
        $this->stock += $stock;
    }
}
