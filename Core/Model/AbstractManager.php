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

    protected $_selectAll;
    protected $_selectFrom;
    protected $_selectFromWhere;

    public function __construct()
    {
        $this->_req=new BddAction();
        $this->_selectAll='SELECT * FROM ';
        $this->_selectFrom=$this->_selectAll . static::$table;
        $this->_selectFromWhere=$this->_selectFrom . ' WHERE ';

    }

    /**
     * Fonction retournant l'ensemble des données de la table
     * @return mixed
     */
    protected function getAll()
    {
        return $this->query($this->_selectFrom . ' order by ' . static::$order_by . ';');
    }

    /**
     * Summary of find : Recherche un enregistrement
     * @param mixed $identifiant
     * @return mixed
     */
    public function find($identifiant)
    {
        $tatement=$this->_selectFromWhere . static::$id . '=:id;';
        $arguments=['id'=>$identifiant];
        return $this->query ($tatement,$arguments,true);
    }
    public function findByField($field, $value)
    {
        $tatement=$this->_selectFromWhere . $field . '=:value;';
        $arguments=['value'=>$value];
        return $this->query ($tatement,$arguments,true);
    }

    public function exist($filedName,$value,$id=null):bool
    {
        if(isset($id))
        {
            $present = $this->query(
                $this->_selectFromWhere . $filedName . '=:fieldName AND ' . static::$id . '!=:id;'
                , [
                'fieldName'=>$value ,
                'id'=>$id
                ],
                true);
        }
        else
        {
            $present = $this->query(
                $this->_selectFromWhere . $filedName . '=:fieldName;'
                , ['fieldName'=>$value ],
                true);

        }
        return $present;
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


        if($this->query('INSERT INTO '. static::$table . ' SET ' . $sql_part, $attributes, true))
        {
            return $this->_req->lastInsertId();
        }
        return false;

    }
    public function update($id, $fields)
    {
        $sql_parts = [];
        $attributes = [];
        foreach ($fields as $k => $v) {
            $sql_parts[] = $k . '= ?';
            $attributes[] = $v;
        }
        $attributes[] = $id;
        $sql_part =  implode(', ', $sql_parts);
        return $this->query('UPDATE '. static::$table . ' SET '. $sql_part . ' WHERE ' . static::$id . '=? ', $attributes, true);
    }

    public function delete($id)
    {
        return $this->query('DELETE FROM '. static::$table . ' WHERE ' . static::$id . '= ?', [$id], true);
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
        {
            $datas=$this->_req->prepare($requete, $arguments, $only_one);
        }
        else
        {
            $datas=$this->_req->query($requete,$only_one);
        }

        $dataReturn=null;
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
        }
        return $dataReturn;
    }

}