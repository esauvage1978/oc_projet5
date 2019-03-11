<?php

namespace ES\Core\Toolbox;

/**
 * Url short summary.
 *
 * Url description.
 *
 * @version 1.0
 * @author ragus
 */
class Url
{
    /**
     * @param int $lenght : Permet de générer une chaine de caractères aléatoire de 60 caractères par défaut
     * @return String
     */
    public static function to(): String
    {
        return ES_ROOT_PATH_WEB_INDEX . implode('/',func_get_args());
    }
}