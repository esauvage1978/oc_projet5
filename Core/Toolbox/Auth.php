<?php

namespace ES\Core\ToolBox;

/**
 * User short summary.
 *
 * User description.
 *
 * @version 1.0
 * @author ragus
 */
class Auth
{

    /**
     * @param String $password : Hash le password passé en paramètre en le bornant de SALT
     * @return String
     */
    public static function password_crypt(String $password): String
    {
        return \password_hash(ES_PASSWORD_SALT_START. $password .ES_PASSWORD_SALT_END,PASSWORD_BCRYPT);
    }

    /**
     * @param string $password_saisie
     * @param string $password_bdd
     * @return bool
     * Vérifie le password saisie et le password en bdd
     */
    public static function password_compare(string $password_saisie,string $password_bdd):bool
    {
        return \password_verify(ES_PASSWORD_SALT_START. $password_saisie .ES_PASSWORD_SALT_END,$password_bdd);
    }

    /**
     * @param int $lenght : Permet de générer une chaine de caractères aléatoire de 60 caractères par défaut
     * @return String
     */
    public static function str_random(int $lenght = 60): String
    {
        $alphabet = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        return substr(str_shuffle(str_repeat($alphabet, $lenght)), 0, $lenght);
    }
}