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
        $this->pdo=PDO2::getInstance();
    }

    public function query($requete,$class_name)
    {
        $req= $this->pdo->query($requete);
        $data=$req->fetchAll() ;
        return $data;
    }

    public function prepare($requete,$params,$class_name,$only_one=false)
    {
        $req= $this->pdo->prepare($requete);
        $req->execute($params);
        //$req->setFetchMode(\PDO::FETCH_CLASS, $class_name);
        if($only_one)
            $data=$req->fetch();
        else
            $data=$req->fetchAll() ;
        return $data;
    }
}