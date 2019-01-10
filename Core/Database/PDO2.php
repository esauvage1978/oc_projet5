<?php
/**
 * Created by PhpStorm.
 * User: esauvage1978
 * Date: 16/12/2018
 * Time: 19:57
 */

namespace ES\Core\Database;

use PDO;

require_once ESES_ROOT_PATH_FAT . 'config\\bdd.php';

/**
 * Classe implémentant le singleton pour PDO
 */
class PDO2
{

    /**
     * @var
     */
    private static $_instance;

    /* Constructeur : héritage public obligatoire par héritage de PDO */
    /**
     * PDO2 constructor.
     */
    public function __construct()
    {

    }
    // End of PDO2::__construct() */

    /* Singleton */
    /**
     * @return PDO ; création de l'instance de PDO - voir fichier parametre.php
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance))
        {
            self::$_instance = new PDO('mysql:host=' . ES_DB_HOST . ';dbname=' . ES_DB_NAME . ';charset=utf8', ES_DB_USERNAME, ES_DB_PASSWORD);
            self::$_instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$_instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        //Debug::var_dump("Appel PDO 2");
        return self::$_instance;
    }
    // End of PDO2::getInstance() */
}

// end of file */
