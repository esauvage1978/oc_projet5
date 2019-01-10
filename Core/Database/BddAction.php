<?php
/**
 * Created by PhpStorm.
 * User: esauvage1978
 * Date: 02/01/2019
 * Time: 10:27
 */

namespace ES\Core\Database;


class BddAction
{
    private $_pdo;

    public function __construct()
    {
        $this->_pdo=PDO2::getInstance();
    }

    public function query($requete)
    {
        $req= $this->_pdo->query($requete);
        $data=$req->fetchAll() ;
        return $data;
    }

    public function prepare($requete,$params,$only_one=false)
    {
        $req= $this->_pdo->prepare($requete);
        $req->execute($params);
        if($only_one)
            $data=$req->fetch();
        else
            $data=$req->fetchAll() ;
        return $data;
    }
}