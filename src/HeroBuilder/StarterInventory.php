<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 16/12/2017
 * Time: 00:23
 */

namespace App\HeroBuilder;

use App\Entity\Hero;
use App\Entity\Story;
use App\Entity\Weapon;
use App\Entity\ConsumableItem;
use App\Entity\SpecialItem;
use Doctrine\ORM\EntityManager;

class StarterInventory
{

    private $weaponStarter;
    private $specialItemStarter;
    private $consumableItemStarter;
    private $gold;
    private $em;


    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $this->weaponStarter = new Weapon();
        $this->specialItemStarter = new SpecialItem();
        $this->consumableItemStarter = new ConsumableItem();
    }

    public static function setStarterItem(Story $story, Hero $hero, EntityManager $em)
    {
        $diceResult = mt_rand(1, 1);

        switch ($diceResult) {
            case 1:
                $hero->addWeapon($em->getRepository(Weapon::class)->findOneBy(["story" => $story,"name" => "Sword"]));
                break;
            case 2:
                $hero->addSpecialItem($em->getRepository(SpecialItem::class)->findOneBy(["story" => $story,"name" => "Helmet"]));
                break;
            case 3:
                $hero->addConsumableItem($em->getRepository(ConsumableItem::class)->findOneBy(["story" => $story,"name" => "Meal"]));
                break;
            case 4:
                $hero->addSpecialItem($em->getRepository(SpecialItem::class)->findOneBy(["story" => $story, "name" => "Chainmail Waistcoast"]));
                break;
            case 5:
                $hero->addWeapon($em->getRepository(Weapon::class)->findOneBy(["story" => $story,"name" => "Mace"]));
                break;
        }

        return $hero;
    }
}