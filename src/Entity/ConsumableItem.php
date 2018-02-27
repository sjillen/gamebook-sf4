<?php
/* for potion, food and any other consumable or throwable item picked along the adventure */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\ItemBase;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConsumableItemRepository")
 */
class ConsumableItem extends ItemBase
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $removable;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="Story", inversedBy="consumableItems")
     */
    protected $story;

    public function __construct()
    {
        $this->quantity = 1;
    }

    /* Setters and Getters */

    public function getId() : int 
    {
        return $this->id;
    }

    public function setId($id) : void 
    {
        $this->id = $id;
    }

    public function getRemovable() : ?bool
    {
        return $this->removable;
    }

    public function setRemovable($removable) : void 
    {
        $this->removable = $removable;
    }

    public function getQuantity() : ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity) : void
    {
        $this->quantity = $quantity;
    }

    public function getStory(): ?Story
    {
        return $this->story;
    }

    public function setStory(?Story $story) : void
    {
        $this->story = $story;
    }

}
