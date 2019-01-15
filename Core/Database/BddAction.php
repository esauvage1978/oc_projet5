<?php
/**
 * Created by PhpStorm.
 * User: esauvage1978
 * Date: 02/01/2019
 * Time: 10:27
 */

namespace ES\Core\Database;

/**
 * Class de gestion des requêtes
 */
class BddAction
{
    private $_pdo;

    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->_pdo=PDO2::getInstance();
    }

    public function query($requete)
    {
        return $this->_pdo->query($requete)->fetchAll();
    }

    public function prepare($requete,$params,$only_one=false)
    {
        $pdoStatement= $this->_pdo->prepare($requete);
        $pdoStatement->execute($params);
        if($only_one)
        {
            $data=$pdoStatement->fetch();
        }
        else
        {
            $data=$pdoStatement->fetchAll() ;
        }
        return $data;
    }
}