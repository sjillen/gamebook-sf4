<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 12/12/2017
 * Time: 20:33
 */

namespace App\Utils;


/**
 * Imported from the symfony 4 demo
 */
class Slugger
{
    public static function slugify(string $string): string
    {
        return preg_replace('/\s+/', '-', mb_strtolower(trim(strip_tags($string)), 'UTF-8'));
    }
}
