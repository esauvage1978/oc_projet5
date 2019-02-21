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
    protected $req;

    protected static $table='';
    protected static $order_by='';
    protected static $id='';

    protected $_selectAll;
    protected $_selectCount;
    protected $where;

    public function __construct()
    {
        $select =' SELECT ';
        $from=' FROM ';
        $this->where=' WHERE ';
        $all=' * ';
        $count=' count(*) ';

        $this->_req=new BddAction();
        $this->_selectAll=$select . $all . $from . static::$table . $this->where;
        $this->_selectCount= $select . $count . $from . static::$table . $this->where;
    }

    /**
     * Fonction retournant l'ensemble des données de la table
     * @return mixed
     */
    protected function getAll()
    {
        return $this->query($this->_selectAll . '1=1  ORDER BY ' . static::$order_by . ';');
    }
    protected function count()
    {
        return $this->query($this->_selectCount . '1=1  ;',null,true)['count(*)'];
    }
    /**
     * Summary of find : Recherche un enregistrement
     * @param mixed $identifiant
     * @return mixed
     */

    protected function findByField($field,$value)
    {
        $query= new QueryBuilder();
        $query->select('*')->from(static::$table )
            ->where($field . '=:value');
        $arguments=['value'=>$value];
        $retour= $this->query($query->render() ,$arguments,true);
        return $this->createClassTable($retour);
    }

    protected function createClassTable($data)
    {
        if(!$data) {
            return new static::$classTable([]);
        } else {
            return new static::$classTable($data);
        }
    }

    protected function exist($fieldName,$value,$id=null):bool
    {
        if(isset($id))
        {
            $present = $this->query(
                $this->_selectCount . $fieldName . '=:fieldName AND ' . static::$id . '!=:id;'
                , [
                'fieldName'=>$value ,
                'id'=>$id
                ],
                true);
        }
        else
        {
            $present = $this->query(
                $this->_selectCount . $fieldName . '=:fieldName;'
                , ['fieldName'=>$value ],
                true);
        }

        return $present[array_keys($present)[0]];
    }

    protected function create($datas)
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
        return $this->query('UPDATE '. static::$table . ' SET '. $sql_part . $this->where . static::$id . '=? ', $attributes, true);
    }

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

    protected function delete($id)
    {
        return $this->query('DELETE FROM '. static::$table . $this->where . static::$id . '= ?', [$id], true);
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
        if (isset($datas)) {
            if($onlyOne) {
                $dataReturn = $datas;
            } else {
                $dataReturn=array();
                if($createClass) {
                    foreach ($datas as $data) {
                        $dataReturn[] = $this->createClassTable($data);
                    }
                } else {
                    $dataReturn=$datas;
                }
            }
        }
        return $dataReturn;
    }

}