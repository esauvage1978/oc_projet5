<?php

namespace ES\Core\Model;

use \ES\Core\Database\BddAction;
use ES\Core\Database\QueryBuilder;

abstract class AbstractManager
{
    /**
     * Variable contenant la connexion à la bdd
     * @var mixed
     */
    protected $_req;

    /**
     * Variable pour la construction des requêtes
     * @var QueryBuilder
     */
    protected $_queryBuilder;

    protected static $table='';
    protected static $order_by='';
    protected static $id='';


    public function __construct()
    {
        $this->_req=new BddAction();
        $this->_queryBuilder =new QueryBuilder();
    }

    /**
     * Fonction retournant l'ensemble des données de la table sous forme de class
     * @return mixed
     */
    protected function getAll()
    {
        return $this->query(
            $this->_queryBuilder->select('*')
            ->from(static::$table )
            ->orderBy(static::$order_by)->render());
    }

    /**
     * retourne le nombre d'enregistrement total de la table
     * @return mixed
     */
    protected function count() : string
    {
        return $this->query(
            $this->_queryBuilder->select('count(*)')
            ->from(static::$table )
            ->orderBy(static::$order_by)->render(),null,true,false)['count(*)'];
    }

    /**
     * Summary of find : Recherche un enregistrement et retourne la class
     * @param mixed $identifiant
     * @return mixed
     */
    protected function findByField($field,$value)
    {
        return $this->query(
            $this->_queryBuilder->select('*')
            ->from(static::$table )
            ->where($field . '=:value')->render(),
            ['value'=>$value],true,true);
    }

    /**
     * Summary of exist
     * @param mixed $fieldName
     * @param mixed $value
     * @param mixed $id
     * @return mixed
     */
    protected function exist($fieldName,$value,$id=null):bool
    {
        if(isset($id)) {
            $present = $this->query(
                            $this->_queryBuilder
                            ->select('count(*)')
                            ->from(static::$table )
                            ->where( $fieldName . '=:fieldName')
                            ->where( static::$id . '!=:id')
                            ->render(),
                            [
                            'fieldName'=>$value ,
                            'id'=>$id
                            ],true,false);
        } else {
            $present = $this->query(
                            $this->_queryBuilder
                            ->select('count(*)')
                            ->from(static::$table )
                            ->where( $fieldName . '=:fieldName')
                            ->render(),
                            [
                            'fieldName'=>$value
                            ],true,false);
        }

        return $present[array_keys($present)[0]];
    }

    /**
     * creation d'une enregistrement
     * @param mixed $datas
     * @return \boolean|string
     */
    protected function create($datas)
    {
        $sql_parts = [];
        $attributes = [];
        foreach ($datas as $k => $v) {
            $sql_parts[] = $k. '= ?';
            $attributes[] = $v;
        }
        $sql_part =  implode(', ', $sql_parts);

        if($this->query('INSERT INTO '. static::$table . ' SET ' . $sql_part, $attributes, true,false))
        {
            return $this->_req->lastInsertId();
        }
        return false;

    }

    /**
     * Mise à jour d'un enregistrement
     * @param mixed $id
     * @param mixed $fields
     * @return mixed
     */
    protected function update($id, $fields)
    {
        $sql_parts = [];
        $attributes = [];
        foreach ($fields as $k => $v) {
            $sql_parts[] = $k . '= ?';
            $attributes[] = $v;
        }
        $attributes[] = $id;
        $sql_part =  implode(', ', $sql_parts);
        return $this->query('UPDATE '. static::$table . ' SET '. $sql_part . ' WHERE ' . static::$id . '=? ',
            $attributes, true,false);
    }

    /**
     * Suppression d'un enregistrement
     * @param mixed $id
     * @return mixed
     */
    protected function delete($id)
    {
        return $this->query(
            'DELETE FROM '. static::$table . ' WHERE ' . static::$id . ':=value', ['value'=>$id]
            ,true,false); 
    }

    /**
     * Récupération d'un tableau pour les contrôle select
     * @param mixed $key
     * @param mixed $value
     * @param mixed $firstElementEmpty
     * @return array
     */
    protected function getArrayForSelect($key,$value,$firstElementEmpty=false)
    {
        $query= new QueryBuilder();
        $query->select($key,$value)
            ->from(static::$table )
            ->orderBy($value);
        $liste=$this->query($query->render(),null,false,false);
        $select=[];
        if($firstElementEmpty) {
            $select[]='';
        }
        foreach ($liste as $element) {
            $select[$element[$key]]=$element[$value];
        }
        return $select;
    }


    /**
     * Summary of query : exécute une requête
     * @param mixed $requete
     * @param mixed $arguments
     * @param mixed $only_one
     * @return mixed
     */
    protected function query($requete, $arguments=null,$onlyOne=false,$createClass=true)
    {
        if (isset($arguments)) {
            $datas=$this->_req->prepare($requete, $arguments, $onlyOne);
        } else {
            $datas=$this->_req->query($requete,$onlyOne);
        }

        $dataReturn=null;

        if($createClass && !$onlyOne ) {
            $dataReturn=array();
            foreach ($datas as $data) {
                $dataReturn[] = $this->createClassTable($data);
            }
        } elseif($createClass && $onlyOne ) {
            $dataReturn=$this->createClassTable($datas);
        } elseif(!$createClass && !$onlyOne ) {
            $dataReturn=array();
            $dataReturn= $datas;
        } else {
            $dataReturn= $datas;
        }


        return $dataReturn;
    }

    /**
     * génère une classTable à partir des données
     * @param mixed $data
     * @return object
     */
    protected function createClassTable($data)
    {
        if(!$data || !isset($data)) {
            return new static::$classTable([]);
        } else {
            return new static::$classTable($data);
        }
    }

}