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
    public function getAll()
    {
        return $this->query($this->_selectAll . '1=1  ORDER BY ' . static::$order_by . ';');
    }

    /**
     * Summary of find : Recherche un enregistrement
     * @param mixed $identifiant
     * @return mixed
     */

    public function findByField($field,$value)
    {

        $tatement=$this->_selectAll . $field . '=:value;';
        $arguments=['value'=>$value];
        $retour= $this->query ($tatement,$arguments,true);
        return $this->createClassTable($retour);
    }

    public function createClassTable($data)
    {
        if(!$data) {
            return new static::$classTable([]);
        } else {
            return new static::$classTable($data);
        }
    }

    public function exist($fieldName,$value,$id=null):bool
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
        return $this->query('UPDATE '. static::$table . ' SET '. $sql_part . $this->where . static::$id . '=? ', $attributes, true);
    }

    public function delete($id)
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
    public function query($requete, $arguments=null,$only_one=false)
    {
        if (isset($arguments)) {
            $datas=$this->_req->prepare($requete, $arguments, $only_one);
        } else {
            $datas=$this->_req->query($requete,$only_one);
        }

        $dataReturn=null;
        if (isset($datas)) {
            if($only_one) {
                $dataReturn = $datas;
            } else {
                $dataReturn=array();
                foreach ($datas as $data) {
                    $dataReturn[] = $this->createClassTable($data);
                }
            }
        }
        return $dataReturn;
    }

}