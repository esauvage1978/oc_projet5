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
class CommentTable extends AbstractTable implements ITable
{
    protected static $_prefixe='bco_';

    private $_id;
    private $_createDate;
    private $_createUserRef;
    private $_content;
    private $_moderatorDate;
    private $_moderatorUserRef;
    private $_moderatorStatus;

    const ID= 'bco_id';
    const CREATE_DATE= 'bco_create_date';
    const CREATE_USER_REF= 'bco_create_user_ref';
    const CONTENT= 'bco_content';
    const MODERATOR_DATE= 'bco_moderator_date';
    const MODERATOR_USER_REF= 'bco_moderator_user_ref';
    const MODERATOR_STATUS= 'bco_moderator_status';

    private $_msgBadDate='La date est incorrecte.';

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
    #region CREATE DATE
    public function getCreateDate()
    {
        return \date('d/m/Y H:i',strtotime($this->_createDate));
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
    #endregion
    #region TITLE
    public function getContent()
    {
        return $this->_content;
    }
    public function setContent($value)
    {
        if( empty($value)  )
        {
            throw new \InvalidArgumentException('Le commentaire est vide.');
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
        $this->_chapo=htmlentities($value);
    }
    #endregion

    #region USER CREATE REF
    public function getUserCreateRef()
    {
        return $this->_createUserRef;
    }
    public function setUserCreateRef($value)
    {
        if(empty($value))
        {
            throw new \InvalidArgumentException('Les données de l``utilisateur ne sont pas présentes.');
        }
        $this->_createUserRef=$value;

    }
    #endregion
    #region DATE MODIFY
    public function getDateModify()
    {
        return $this->_dateModify;
    }
    public function setDateModify($value)
    {
        if(!empty($value) &&
           !\DateTime::createFromFormat(ES_NOW, $value))
        {
            throw new \InvalidArgumentException($this->_msgBadDate);
        }

        $this->_dateModify=$value;
    }
    #endregion
    #region USER MODIFY REF
    public function getUserModifyRef()
    {
        return $this->_userModifyRef;
    }
    public function setUserModifyRef($value)
    {
        $this->_userModifyRef=$value;
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
        $this->_StateRef=$value;
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

}