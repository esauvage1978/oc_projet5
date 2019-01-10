<?php

namespace ES\Core\Database;

use \ES\Core\Database\BddAction;

class AbstractManager
{
    /**
     * Variable contenant la connexion à la bdd
     * @var mixed
     */
    protected $req;

    protected static $table;
    protected static $class_name;
    protected static $order_by;
    protected static $id;


    public function __construct()
    {
        $this->_req=new BddAction();
    }

    protected function setUnitClass($data)
    {
        if($data==false)
        {
            return false;
        }
        else
        {
            return new static::$class_name($data);
        }
    }

    /**
     * Fonction retournant l'ensemble des données de la table
     * @return mixed
     */
    protected function getAll()
    {
        $req='SELECT * FROM ' . static::$table . ' order by ' . static::$order_by . ';';
        return $this->query($req);
    }

    /**
     * Summary of find : Recherche un enregistrement
     * @param mixed $identifiant
     * @return mixed
     */
    public function find($identifiant)
    {
        $req='SELECT * FROM ' . static::$table . ' WHERE ' . static::$id . '=:id;';
        $arguments=['id'=>$identifiant];
        return $this->query ($req,$arguments,true);
    }

    /**
     * Summary of query : exécute une requête
     * @param mixed $req
     * @param mixed $arguments
     * @param mixed $only_one
     * @return mixed
     */
    public function query($req, $arguments=null,$only_one=false)
    {
        if($arguments)
        {
            $datas=$this->_req->prepare($req, $arguments, static::$class_name, $only_one);

        }
        else
        {
            $datas=$this->_req->query($req, static::$class_name, $only_one);
        }
        if (!isset($datas))
        {
            return null;
        }
        else
        {
            if($only_one)
            {
                $dataReturn = $this->setUnitClass($datas);
            }
            else
            {
                $dataReturn=array();
                foreach ($datas as $data)
                {
                    $dataReturn[] = $this->setUnitClass($data);
                }
            }
            return $dataReturn;
        }
        return $dataReturn;
    }
}