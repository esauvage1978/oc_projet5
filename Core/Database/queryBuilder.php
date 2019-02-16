<?php

namespace ES\Core\Database;
/**
 * QueryBuilder short summary.
 *
 * QueryBuilder description.
 *
 * @version 1.0
 * @author ragus
 */
class QueryBuilder
{
    private $_fields=[];
    private $_condition=[];
    private $_from=[];
    private $_orderBy=[];
    private $_innerJoin=[];
    private $_outerJoin=[];

    public function select()
    {
        $this->_fields=func_get_args();

        return $this;
    }

    public function where()
    {
        foreach(func_get_args() as $args) {
            $this->_condition[]=$args;
        }
        return $this;
    }

    public function from()
    {
        $this->_from=func_get_args();
        return $this;
    }

    public function innerJoin()
    {
        $this->_innerJoin=func_get_args();
        return $this;
    }

    public function outerJoin()
    {
        $this->_outerJoin=func_get_args();
        return $this;
    }

    public function orderBy()
    {
        $this->_orderBy=func_get_args();
        return $this;
    }

    public function render()
    {
        return 'SELECT ' . implode(', ',$this->_fields) .
               ' FROM '  . implode(' , ',$this->_from) .
               (count($this->_innerJoin)?' INNER JOIN ' . implode(' INNER JOIN  ',$this->_innerJoin) :'').
               (count($this->_outerJoin)?' LEFT OUTER JOIN ' . implode(' LEFT OUTER JOIN  ',$this->_outerJoin) :'').
               (count($this->_condition)?  ' WHERE ' . implode(' AND ',$this->_condition) :'').
               (count($this->_orderBy)?  ' ORDER BY ' . implode(', ',$this->_orderBy):'') . ';';
    }
}