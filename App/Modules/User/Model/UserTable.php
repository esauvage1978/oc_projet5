<?php

namespace ES\App\Modules\User\Model;
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
    private $_accreditation;
    private $_actif;
    private $_actif_date;

    private $_msgBadDate='La date est incorrecte.';

    #region GET
    public function getId()
    {
        return $this->_id;
    }
    public function getIdentifiant()
    {
        return $this->_identifiant;
    }
    public function getMail()
    {
        return $this->_mail;
    }
    public function getPassword()
    {
        return $this->_secret;
    }
    public function getForgetHash()
    {
        return $this->_forgetHash;
    }
    public function getForgetDate()
    {
        return $this->_forgetDate;
    }
    public function getValidAccountHash()
    {
        return $this->_validAccountHash;
    }
    public function getValidAccountDate()
    {
        return $this->_validAccountDate;
    }
    public function getAccreditation()
    {
        return $this->_accreditation;
    }
    public function getAccreditationLabel()
    {
        return ES_ACCREDITATION[$this->_accreditation];
    }
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
        return $this->_actif_date;
    }
    #endregion

    #region SETTER
    public function setId($value)
    {
        if(empty($value) ||
           !filter_var($value,FILTER_VALIDATE_INT) )
        {
            throw new \InvalidArgumentException('Les informations de l\'id sont incorrectes.' );
        }
        $this->_id=$value;
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
    public function setPassword($value)
    {
        if(empty($value) ||
           strlen($value)!=60 )
        {
            throw new \InvalidArgumentException('La longueur du mot de passe est incorrecte. (' . strlen($value) . ' caractères)');
        }
        $this->_secret=$value;
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
    public function setForgetDate($value)
    {
        if(!empty($value) &&
           !\DateTime::createFromFormat(ES_NOW, $value))
        {
            throw new \InvalidArgumentException($this->_msgBadDate);
        }

        $this->_forgetDate=$value;
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
    public function setAccreditation($value)
    {
        if(!filter_var($value,FILTER_VALIDATE_INT) ||
            $value>4) {
            throw new \InvalidArgumentException('Les données d\'accrédication sont incorrectes.' . $value);
        }
        $this->_accreditation=$value;
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

        $this->_actif_date=$value;
    }
    #endregion

    public function hasId():bool
    {
        return !empty($this->_id);
    }
}