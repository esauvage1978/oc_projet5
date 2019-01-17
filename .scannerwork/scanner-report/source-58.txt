<?php
/**
 * Created by PhpStorm.
 * User: ragus
 * Date: 18/12/2018
 * Time: 08:37
 */

namespace core\toolbox;

/**
 * Class ToolBox : Contient des méthodes statiques
 * @package ES\Combat\Model
 */
class ToolBox
{



    public static function debug($myArray, $makeDie=false)
    {
        echo "<pre>" . print_r($myArray) . "</pre>";
        if ($makeDie)
        {
            die();
        }
    }
}
