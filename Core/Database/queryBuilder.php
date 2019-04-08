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
    const COUNT='count(*)';
    private $_fields=[];
    private $_condition=[];
    private $_from=[];
    private $_orderBy=[];
    private $_innerJoin=[];
    private $_outerJoin=[];
    private $_groupBy=[];
    private $_having=[];
    private $_limit=[];

    private function flush()
    {
        $this->_fields=[];
        $this->_condition=[];
        $this->_from=[];
        $this->_orderBy=[];
        $this->_innerJoin=[];
        $this->_outerJoin=[];
        $this->_groupBy=[];
        $this->_having=[];
        $this->_limit=[];
    }

    public function select()
    {
        $this->flush();

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
    public function groupBy()
    {
        $this->_groupBy=func_get_args();
        return $this;
    }
    public function having()
    {
        $this->_having=func_get_args();
        return $this;
    }
    public function orderBy()
    {
        $this->_orderBy=func_get_args();
        return $this;
    }
    public function limit()
    {
        foreach(func_get_args() as $args) {
            $this->_limit[]=$args;
        }
        return $this;
    }
    public function render()
    {
        return 'SELECT ' . implode(', ',$this->_fields) .
               ' FROM '  . implode(' , ',$this->_from) .
               (count($this->_innerJoin)?' INNER JOIN ' . implode(' INNER JOIN  ',$this->_innerJoin) :'').
               (count($this->_outerJoin)?' LEFT OUTER JOIN ' . implode(' LEFT OUTER JOIN  ',$this->_outerJoin) :'').
               (count($this->_condition)?  ' WHERE ' . implode(' AND ',$this->_condition) :'').
               (count($this->_groupBy)?  ' GROUP BY ' . implode(', ',$this->_groupBy):'') .
               (count($this->_having)?  ' HAVING ' . implode(', ',$this->_having):'') .
               (count($this->_orderBy)?  ' ORDER BY ' . implode(', ',$this->_orderBy):'') .
               (count($this->_limit)?  ' LIMIT ' . implode(', ',$this->_limit):'') . ';';
    }


}