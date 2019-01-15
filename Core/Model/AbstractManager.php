<?php

namespace ES\Core\Model;

use \ES\Core\Database\BddAction;

abstract class AbstractManager
{
    /**
     * Variable contenant la connexion à la bdd
     * @var mixed
     */
    protected $req;

    protected static $table='';
    protected static $order_by='';
    protected static $id='';


    public function __construct()
    {
        $this->_req=new BddAction();
    }

    /**
     * Fonction retournant l'ensemble des données de la table
     * @return mixed
     */
    protected function getAll()
    {
        return $this->query('SELECT * FROM ' . static::$table . ' order by ' . static::$order_by . ';');
    }

    /**
     * Summary of find : Recherche un enregistrement
     * @param mixed $identifiant
     * @return mixed
     */
    public function find($identifiant)
    {
        $tatement='SELECT * FROM ' . static::$table . ' WHERE ' . static::$id . '=:id;';
        $arguments=['id'=>$identifiant];
        return $this->query ($tatement,$arguments,true);
    }

    public function exist($filedName,$value,$id=null)
    {
        $nbr_data = $this->query(
            'SELECT * FROM ' . static::$table . ' WHERE ' . $filedName . '=:fieldName;'
            , array('fieldName'=>$value ),
            true);

        return !($nbr_data===false);
    }

    public function create($datas)
    {
        $sql_parts = [];
        $attributes = [];
        foreach ($datas as $k => $v) {
            $sql_parts[] = $k. '= ?';
            $attributes[] = $v;
        }
        $sql_part =  implode(', ', $sql_parts);

        return $this->query('INSERT INTO '. static::$table . ' SET ' . $sql_part, $attributes, true);
    }

    /**
     * Summary of query : exécute une requête
     * @param mixed $requete
     * @param mixed $arguments
     * @param mixed $only_one
     * @return mixed
     */
    public function query($requete, $arguments=null,$only_one=false)
    {
        if (isset($arguments))
            $datas=$this->_req->prepare($requete, $arguments, $only_one);
        else
            $datas=$this->_req->query($requete);

        if (isset($datas))
        {
            if($only_one)
            {
                $dataReturn = $datas;
            }
            else
            {
                $dataReturn=array();
                foreach ($datas as $data)
                {
                    $dataReturn[] = $data;
                }
            }
            return $dataReturn;
        }
        return null;
    }

    public function checkInput($value,$required=false)
    {
        if(!$value)
        {
            return false;
        }
        if($required)
        {
            if (!isset($value) || empty($value))
            {
                return false;
            }
        }
        return true;
    }
}