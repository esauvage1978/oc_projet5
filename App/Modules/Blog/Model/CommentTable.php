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
    private $_moderatorState;
    private $_article_ref;

    const ID= 'bco_id';
    const CREATE_DATE= 'bco_create_date';
    const CREATE_USER_REF= 'bco_create_user_ref';
    const CONTENT= 'bco_content';
    const MODERATOR_DATE= 'bco_moderator_date';
    const MODERATOR_USER_REF= 'bco_moderator_user_ref';
    const MODERATOR_STATE= 'bco_moderator_state';
    const ARTICLE_REF='bco_article_ref';

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
    #endregion
    #region CREATE USER REF
    public function getCreateUserRef()
    {
        return $this->_createUserRef;
    }
    public function setCreateUserRef($value)
    {
        $this->_createUserRef=$value;
    }
    #endregion
    #region CONTENT
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

    #region CREATE USER REF
    public function getArticleRef()
    {
        return $this->_article_ref;
    }
    public function setArticleRef($value)
    {
        if(empty($value))
        {
            throw new \InvalidArgumentException('Les données de l\'article ne sont pas présentes.');
        }
        $this->_article_ref=$value;

    }
    #endregion
    #region MODERATOR DATE
    public function getModeratorDate()
    {
        return $this->_moderatorDate;
    }
    public function setModeratorDate($value)
    {
        if(!empty($value) &&
           !\DateTime::createFromFormat(ES_NOW, $value))
        {
            throw new \InvalidArgumentException($this->_msgBadDate);
        }

        $this->_moderatorDate=$value;
    }
    #endregion
    #region MODERATOR USER REF
    public function getModeratorUserRef()
    {
        return $this->_moderatorUserRef;
    }
    public function setModeratorUserRef($value)
    {
        $this->_moderatorUserRef=$value;
    }
    #endregion
    #region STATE
    public function getModeratorState()
    {
        return $this->_moderatorState;
    }
    public function setModeratorState($value)
    {
        $this->_moderatorState=$value;
    }
    #endregion

    public function toArray():array
    {
        return [
                self::ID=>$this->getId(),
                self::CREATE_DATE=>$this->getCreateDate(),
                self::CREATE_USER_REF=>$this->getCreateUserRef(),
                self::CONTENT=>$this->getContent(),
                self::MODERATOR_DATE=>$this->getModeratorDate(),
                self::MODERATOR_USER_REF=>$this->getModeratorUserRef(),
                self::MODERATOR_STATE=>$this->getModeratorState(),
                self::ARTICLE_REF=>$this->getArticleRef()
            ];
    }
}