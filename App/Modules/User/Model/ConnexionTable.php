<?php

namespace ES\App\Modules\User\Model;

use \ES\Core\Model\AbstractTable;
use \ES\Core\Model\ITable;

class ConnexionTable extends AbstractTable implements ITable
{
    protected static $_prefixe='bcon_';

    private $_id;
    private $_date;
    private $_ip;
    private $_nbr_connexion;
    private $_user_ref;

    const ID='bcon_id';
    const DATE='bcon_date';
    const IP='bcon_ip';
    const NBR_CONNEXION='bcon_nbr_connexion';
    const USER_REF='bcon_user_ref';

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
                throw new \InvalidArgumentException('Le numéro de connexion est incorrect : ' . $value );
            }
        }
    }
    #endregion
    #region NBR connexion
    public function getNbrConnexion()
    {
        return $this->_nbr_connexion;
    }
    public function setNbrConnexion($value)
    {
        if(!empty($value)) {
            if(filter_var($value,FILTER_VALIDATE_INT )) {
                $this->_nbr_connexion=$value;
            } else {
                throw new \InvalidArgumentException('Le numéro de connexion est incorrect : ' . $value );
            }
        }
    }
    #endregion
    #region IP
    public function getIp()
    {
        return $this->_ip;
    }
    public function setIp($value)
    {
        if( empty($value) ||
            strlen($value)>15 )
        {
            throw new \InvalidArgumentException('La longueur de l\'adresse IP doit être inférieur à 15 caractères. (' . $value . ')');
        }
        $this->_ip=$value;
    }
    #endregion

    #region USER REF
    public function getUserRef()
    {
        return $this->_user_ref;
    }
    public function setUserRef($value)
    {
        $this->_user_ref=$value;

    }
    #endregion
    #region MODERATOR DATE
    public function getDate()
    {
        return $this->_date;
    }
    public function setdate($value)
    {
        if(!empty($value) &&
           !\DateTime::createFromFormat(ES_NOW_SHORT, $value))
        {
            throw new \InvalidArgumentException($this->_msgBadDate);
        }

        $this->_date=$value;
    }
    #endregion

    public function toArray():array
    {
        return [
                self::ID=>$this->getId(),
                self::DATE=>$this->getDate(),
                self::IP=>$this->getIp(),
                self::NBR_CONNEXION =>$this->getNbrConnexion(),
                self::USER_REF=>$this->getUserRef()
            ];
    }
    public function isValidAccount():bool
    {
        return !is_null( $this->getValidAccountDate() );
    }
}
