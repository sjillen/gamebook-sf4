<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 16/12/2017
 * Time: 18:46
 */

namespace App\Dice;

class Dice
{
    const MIN = 1;

    public static function DiceRoller(int $diceType) : int
    {
        return mt_rand(self::MIN, $diceType);
    }
}