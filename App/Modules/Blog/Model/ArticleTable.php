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
class ArticleTable extends AbstractTable implements ITable
{
    protected static $_prefixe='ba_';

    private $_id;
    private $_title;
    private $_content;
    private $_chapo;
    private $_createDate;
    private $_createUserRef;
    private $_modifyDate;
    private $_modifyUserRef;
    private $_categoryRef;
    private $_state;
    private $_stateDate;

    const ID= 'ba_id';
    const TITLE= 'ba_title';
    const CONTENT= 'ba_content';
    const CHAPO= 'ba_chapo';
    const CREATE_DATE= 'ba_create_date';
    const CREATE_USER_REF= 'ba_create_user_ref';
    const MODIFY_DATE= 'ba_modify_date';
    const MODIFY_USER_REF= 'ba_modify_user_ref';
    const CATEGORY_REF= 'ba_category_ref';
    const STATE= 'ba_state';
    const STATE_DATE= 'ba_state_date';

    private $_msgBadDate='La date est incorrecte.';
    public function toArray():array
    {
        return [
                self::ID=>$this->getId(),
                self::TITLE=>$this->getTitle(),
                self::CONTENT=>$this->getContent(),
                self::CHAPO=>$this->getChapo(),
                self::CREATE_DATE=>$this->getCreateDate(),
                self::CREATE_USER_REF=>$this->getCreateUserRef(),
                self::MODIFY_DATE=>$this->getModifyDate(),
                self::MODIFY_USER_REF=>$this->getModifyUserRef(),
                self::CATEGORY_REF=>$this->getCategoryRef(),
                self::STATE=>$this->getState(),
                self::STATE_DATE=>$this->getStateDate()
            ];
    }
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
            strlen($value)<=3 ||
            strlen($value)>255 )
        {
            throw new \InvalidArgumentException('La longueur du titre doit être comprise entre 3 et 255 caractères.');
        }
        $this->_title=$value;
    }
    #endregion
    #region CONTENT
    public function getContent()
    {
        return $this->_content;
    }
    public function setContent($value)
    {
        if( empty($value))
        {
            throw new \InvalidArgumentException('Contenu de l\'article vide.');
        }
        $this->_content=$value;
    }
    #endregion
    #region CHAPO
    public function getChapo()
    {
        return $this->_chapo;
    }
    public function setChapo($value)
    {
        if( empty($value))
        {
            throw new \InvalidArgumentException('Contenu du chapo de l\'article vide.');
        }
        $this->_chapo=$value;
    }
    #endregion
    #region CREATE
    public function getCreateDate()
    {
        return $this->_createDate;
    }
    public function setCreateDate($value)
    {
        if(!empty($value) &&
           !\DateTime::createFromFormat(ES_NOW, $value))
        {
            throw new \InvalidArgumentException($this->_msgBadDate);
        }

        $this->_createDate=$value;
    }
    public function getCreateUserRef()
    {
        return $this->_createUserRef;
    }
    public function setCreateUserRef($value)
    {
        if(empty($value))
        {
            throw new \InvalidArgumentException('Les données de l``utilisateur ne sont pas présentes.');
        }
        $this->_createUserRef=$value;

    }
    #endregion
    #region DATE MODIFY
    public function getModifyDate()
    {
        return $this->_dateModify;
    }
    public function setModifyDate($value)
    {
        if(!empty($value) &&
           !\DateTime::createFromFormat(ES_NOW, $value))
        {
            throw new \InvalidArgumentException($this->_msgBadDate);
        }

        $this->_dateModify=$value;
    }
    public function getModifyUserRef()
    {
        return $this->_modifyUserRef;
    }
    public function setModifyUserRef($value)
    {
        $this->_modifyUserRef=$value;
    }
    #endregion
    #region CATEGORY REF
    public function getCategoryRef()
    {
        return $this->_categoryRef;
    }
    public function setCategoryRef($value)
    {
        $this->_categoryRef=$value;
    }
    #endregion
    #region STATE
    public function getState()
    {
        return $this->_state;
    }
    public function setState($value)
    {
        $this->_state=$value;
    }
    #endregion
    #region DATE CREATE
    public function getStateDate()
    {
        return $this->_stateDate;
    }
    public function setStateDate($value)
    {
        if(!empty($value) &&
           !\DateTime::createFromFormat(ES_NOW, $value))
        {
            throw new \InvalidArgumentException($this->_msgBadDate);
        }

        $this->_stateDate=$value;
    }
    #endregion

    public function urlRead():string
    {
        return '##INDEX##blog/article/show/' . $this->getId();
    }
    public function urlModify():string
    {
        return '##INDEX##blog/article/modify/' . $this->getId();
    }
    public function urlReadCategory():string
    {
        return '##INDEX##blog/article/list/category/' . $this->getCategoryRef();
    }
}