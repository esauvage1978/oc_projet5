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

    public function query($requete,$only_one=false)
    {
        $pdoStatement = $this->_pdo->query($requete);
        $data=null;

        if ( strpos($requete, 'UPDATE') === 0 ||
             strpos($requete, 'INSERT') === 0 ||
             strpos($requete, 'DELETE') === 0 )
        {
            $data= $pdoStatement;
        }
        else
        {
            ($only_one)
                    ?
                    $data=$pdoStatement->fetch()
                    :
                    $data=$pdoStatement->fetchAll() ;
        }
        return $data;
    }

    public function prepare($requete,$params,$only_one=false)
    {
        $pdoStatement= $this->_pdo->prepare($requete);
        $data=$pdoStatement->execute($params);

        if ( strpos($requete, 'UPDATE') !== 0 &&
             strpos($requete, 'INSERT') !== 0 &&
             strpos($requete, 'DELETE') !== 0 )
        {
            ($only_one)
                    ?
                    $data=$pdoStatement->fetch()
                    :
                    $data=$pdoStatement->fetchAll() ;
        }
        return $data;
    }

    public function lastInsertId():string
    {
        return $this->_pdo->lastInsertId();
    }
}