<?php
/**
 * Created by PhpStorm.
 * User: esauvage1978
 * Date: 02/01/2019
 * Time: 10:43
 */

namespace ES\Core\Toolbox;


class Debug
{
    public static function var_dump($data)
    {
        echo '<pre>' . print_r($data) . '</pre>';
    }
}