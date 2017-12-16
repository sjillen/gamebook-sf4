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
     * How much it increase or decrease a hero attribute
     * 
     * @ORM\Column(type="integer")
     */
    private $bonusGiven;

    /**
     * Which attribute will be affected
     * 
     * @ORM\Column(type="string")
     */
    private $attributeTargeted;

    /**
     * @ORM\Column(type="boolean")
     */
    private $removable;

    /**
     * @ORM\ManyToOne(targetEntity="Story", inversedBy="consumableItems")
     */
    protected $story;

    /* Setters and Getters */

    public function getId() : int 
    {
        return $this->id;
    }

    public function setId($id) : void 
    {
        $this->id = $id;
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

    public function getRemovable() : ?bool
    {
        return $this->removable;
    }

    public function setRemovable($removable) : void 
    {
        $this->removable = $removable;
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
