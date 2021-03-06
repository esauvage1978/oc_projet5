<?php
/**
 * Created by PhpStorm.
 * User: esauvage1978
 * Date: 28/12/2018
 * Time: 11:42
 */

namespace ES\Core\Autoloader;


class Autoloader
{
    static function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    static function autoload($class_name)
    {
        $class_name = str_replace("ES\\", "", $class_name);
        $class_name = str_replace("\\", "/", $class_name);
        $fichier='../' . $class_name . '.php';
        if (file_exists ($fichier))
        {
            require $fichier;
        }
    }
}