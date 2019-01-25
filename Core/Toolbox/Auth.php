<?php

namespace ES\Core\Toolbox;

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
    // Permet d'entourer le mot de passe de caractère pour réduire le risque de hack par disctionnaire
    const SECRET_SALT_START='lmsqkf';
    const SECRET_SALT_END='mplsqpojnfsmzs';

    /**
     * @param String $secret : Hash le password passé en paramètre
     * @return String
     */
    public static function passwordCrypt(String $secret): String
    {
        return \password_hash(self::SECRET_SALT_START . $secret . self::SECRET_SALT_END, PASSWORD_BCRYPT);
    }

    /**
     * Si hash est présent, compare le mot de passe hashé. SecretCompare doit être hashé
     * @param string $secret
     * @param string $secretCompare
     * @param mixed $hash
     * @return boolean
     */
    public static function passwordCompare(string $secret, string $secretCompare,bool $hash=true):bool
    {
        if($hash) {
            return \password_verify(self::SECRET_SALT_START . $secret . self::SECRET_SALT_END, $secretCompare);
        } else {
            return $secret === $secretCompare;
        }
    }
    
    /**
     * @param int $lenght : Permet de générer une chaine de caractères aléatoire de 60 caractères par défaut
     * @return String
     */
    public static function strRandom(int $lenght = 60): String
    {
        $alphabet = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        return substr(str_shuffle(str_repeat($alphabet, $lenght)), 0, $lenght);
    }
}