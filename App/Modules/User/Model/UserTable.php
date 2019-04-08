<?php

namespace ES\App\Modules\User\Model;

use \ES\Core\Model\AbstractTable;
use \ES\Core\Model\ITable;

class UserTable extends AbstractTable implements ITable
{
    protected static $_prefixe='u_';

    private $_id;
    private $_identifiant;
    private $_mail;
    private $_secret;
    private $_forgetHash;
    private $_forgetDate;
    private $_validAccountHash;
    private $_validAccountDate;
    private $_userRole;
    private $_actif;
    private $_actifDate;

    const ID='u_id';
    const IDENTIFIANT='u_identifiant';
    const MAIL='u_mail';
    const SECRET='u_password';
    const FORGET_HASH='u_forget_hash';
    const FORGET_DATE='u_forget_date';
    const VALID_ACCOUNT_HASH='u_valid_account_hash';
    const VALID_ACCOUNT_DATE='u_valid_account_date';
    const USER_ROLE='u_user_role';
    const ACTIF='u_actif';
    const ACTIF_DATE='u_actif_date';

    private $_msgBadDate='La date est incorrecte.';

    #region ID
    public function hasId():bool
    {
        return !empty($this->_id);
    }
    public function getId()
    {
        return $this->_id;
    }
    public function setId($value)
    {
        if(!empty($value)) {
            if(filter_var($value,FILTER_VALIDATE_INT )) {
                $this->_id=$value;
            } else {
                throw new \InvalidArgumentException('Le numéro d\'utilisateur est incorrect : ' . $value );
            }
        }
    }
    #endregion
    #region IDENTIFIANT
    public function getIdentifiant()
    {
        return $this->_identifiant;
    }
    public function setIdentifiant($value)
    {
        if( empty($value) ||
            strlen($value)<=3 ||
            strlen($value)>45 )
        {
            throw new \InvalidArgumentException('La longueur de l\'identifiant doit être comprise entre 3 et 45 caractères.');
        }
        $this->_identifiant=$value;
    }
    #endregion
    #region MAIL
    public function getMail()
    {
        return $this->_mail;
    }
    public function setMail($value)
    {
        if( empty($value) ||
            strlen($value)>100 ||
            !filter_var($value,FILTER_VALIDATE_EMAIL))
        {
            throw new \InvalidArgumentException('Les informations du mail sont incorrectes.');
        }
        $this->_mail=$value;
    }
    #endregion
    #region SECRET
    public function getPassword()
    {
        return $this->_secret;
    }
    public function setPassword($value)
    {
        if(empty($value) ||
           strlen($value)!=60 )
        {
            throw new \InvalidArgumentException('La longueur du mot de passe est incorrecte. (' . strlen($value) . ' caractères)');
        }
        $this->_secret=$value;
    }
    #endregion
    #region FORGET
    public function getForgetHash()
    {
        return $this->_forgetHash;
    }
    public function setForgetHash($value)
    {
        if(!empty($value) &&
            strlen($value)!=60 )
        {
            throw new \InvalidArgumentException('La longueur de la clé est incorrecte');
        }
        $this->_forgetHash=$value;
    }
    public function getForgetDate()
    {
        return $this->_forgetDate;
    }
    public function setForgetDate($value)
    {
        if(!empty($value) &&
           !\DateTime::createFromFormat(ES_NOW, $value))
        {
            throw new \InvalidArgumentException($this->_msgBadDate);
        }

        $this->_forgetDate=$value;
    }
    #endregion
    #region VALID ACCOUNT HASH
    public function getValidAccountHash()
    {
        return $this->_validAccountHash;
    }
    public function setValidAccountHash($value)
    {
        if(!empty($value) &&
    strlen($value)!=60 )
        {
            throw new \InvalidArgumentException('La longueur de la clé est incorrecte');
        }
        $this->_validAccountHash=$value;
    }
    public function setValidAccountDate($value)
    {
        if(!empty($value) &&
           !\DateTime::createFromFormat(ES_NOW, $value))
        {
            throw new \InvalidArgumentException($this->_msgBadDate);
        }
        $this->_validAccountDate=$value;
    }
    public function getValidAccountDate()
    {
        return $this->_validAccountDate;
    }
    #endregion
    #region ROLE
    public function getUserRole()
    {
        return $this->_userRole;
    }
    Public function getUserRoleLabel()
    {
        return ES_USER_ROLE[$this->_userRole];
    }
    public function setUserRole($value)
    {
        if(!filter_var($value,FILTER_VALIDATE_INT) ||
            $value>4) {
            throw new \InvalidArgumentException('Les données des rôles des utilisateurs sont incorrectes.' . $value);
        }
        $this->_userRole=$value;
    }
    #endregion
    #region ACTIF
    public function getActif()
    {
        return $this->_actif;
    }
    public function getActifLabel()
    {
        if( $this->_actif) {
            return "Compte actif";
        } else {
            return "Compte suspendu le " . $this->getactifDate() ;
        }
    }
    public function getActifDate()
    {
        return $this->_actifDate;
    }
    public function setActif($value)
    {
        if($value!='1' && $value!='0') {
            throw new \InvalidArgumentException('la valeur du paramètre actif est incorrecte.' . $value);
        }
        $this->_actif=$value;
    }
    public function setActifDate($value)
    {
        if(!empty($value) &&
           !\DateTime::createFromFormat(ES_NOW, $value))
        {
            throw new \InvalidArgumentException($this->_msgBadDate);
        }

        $this->_actifDate=$value;
    }
    #endregion

    public function toArray():array
    {
        return [
                self::ID=>$this->getId(),
                self::IDENTIFIANT=>$this->getIdentifiant(),
                self::MAIL=>$this->getMail(),
                self::SECRET=>$this->getPassword(),
                self::FORGET_HASH=>$this->getForgetHash(),
                self::FORGET_DATE=>$this->getForgetDate(),
                self::VALID_ACCOUNT_HASH=>$this->getValidAccountHash(),
                self::VALID_ACCOUNT_DATE=>$this->getValidAccountDate(),
                self::USER_ROLE=>$this->getUserRole(),
                self::ACTIF=>$this->getActif(),
                self::ACTIF_DATE=>$this->getActifDate()
            ];
    }
    public function isValidAccount():bool
    {
        return !is_null( $this->getValidAccountDate() );
    }

    public function urlModify()
    {
        return '##INDEX##user/modify/' .$this->getId();
    }

}
