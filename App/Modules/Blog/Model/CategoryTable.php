<?php

namespace ES\App\Modules\Blog\Model;

use \ES\Core\Model\AbstractTable;
use \ES\Core\Model\ITable;

/**
 * User short summary.
 *
 * User description.
 *
 * @version 1.0
 * @author ragus
 */
class CategoryTable extends AbstractTable implements ITable
{
    protected static $_prefixe='bc_';

    private $_id;
    private $_title;

    const ID= 'bc_id';
    const TITLE= 'bc_title';

    #region ID
    public function getId()
    {
        return $this->_id;
    }
    public function hasId():bool
    {
        return !empty($this->_id);
    }
    public function setId($value)
    {
        if(empty($value) ||
           !filter_var($value,FILTER_VALIDATE_INT) )
        {
            throw new \InvalidArgumentException('Les informations de l\'id sont incorrectes.' );
        }
        $this->_id=$value;
    }
    #endregion
    #region TITLE
    public function getTitle()
    {
        return $this->_title;
    }
    public function setTitle($value)
    {
        if( empty($value) ||
            strlen($value)>20 )
        {
            throw new \InvalidArgumentException('La longueur du titre doit être inférieure à 20 caractères.');
        }
        $this->_title=$value;
    }
    #endregion


    public function toArray():array
    {
        return [
                self::ID=>$this->getId(),
                self::TITLE=>$this->getTitle()
            ];
    }
}