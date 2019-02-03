<?php

namespace ES\Core\Database;

/**
 * queryBuilder short summary.
 *
 * queryBuilder description.
 *
 * @version 1.0
 * @author ragus
 */
class queryBuilder
{
    private $_fields[];
    private $_condition[];
    private $_from[];
    private $_orderBy[];

    public function select()
    {
        $this->_fields=func_get_args();
        return $this;
    }

    public function where()
    {
        foreach(func_get_args() as args) {
            $this->_condition[]=args;
        }
        return $this;
    }

    public function from()
    {
        $this->_from=func_get_args();
        return $this;
    }

    public function orderBy()
    {
        $this->_from=func_get_args();
        return $this;
    }

    public function __toString()
    {
        return 'SELECT ' . implode(', ',$this->_fields) .
               ' FROM '  . implode(' , ',$this->_from) .
               ' WHERE ' . implode(' AND ',$this->_condition) .
               ' ORDER BY ' . implode(', ',$this->_orderby) . ';';
    }




}