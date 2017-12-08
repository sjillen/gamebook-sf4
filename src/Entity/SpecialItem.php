<?php

/* Specific items such as armor or artefact, carried in a specific slot by the hero */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\ItemBase;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpecialItemRepository")
 */
class SpecialItem extends ItemBase
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * The slot where the item is carried by the hero
     * 
     * @ORM\Column(type="string")
     */
    private $slot;

    /* Setters and Getters */

    public function getId() : int 
    {
        return $this->id;
    }

    public function setId($id) : void 
    {
        $this->id = $id;
    }

    public function getSlot() : ?string 
    {
        return $this->slot;
    }

    public function setSlot($slot) : void
    {
        $this->slot = $slot;
    }
}
