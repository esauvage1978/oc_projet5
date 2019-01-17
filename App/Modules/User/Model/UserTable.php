<?php

namespace ES\App\Modules\User\Model;

use \ES\App\Modules\User\Render\UserRenderView;
use \ES\Core\Model\AbstractTable;

/**
 * User short summary.
 *
 * User description.
 *
 * @version 1.0
 * @author ragus
 */
class UserTable extends AbstractTable
{
    protected static $_prefixe='u_';


    private $_u_id;
    private $_u_identifiant;
    private $_u_mail;
    private $_u_password;
    private $_u_forget_hash;
    private $_u_forget_date;
    private $_u_valid_account_hash;
    private $_u_valid_account_date;
    private $_u_accreditation;


    #region GET
    public function getId():string
    {
        return $this->_u_id;
    }
    public function getIdentifiant():string
    {
        return $this->_u_identifiant;
    }
    public function getMail():string
    {
        return $this->_u_mail;
    }
    public function getPassword():string
    {
        return $this->_u_password;
    }
    public function getForgetHash()
    {
        return $this->_u_forget_hash;
    }
    public function getForgetDate()
    {
        return $this->_u_forget_date;
    }
    public function getValidAccountHash()
    {
        return $this->_u_valid_account_hash;
    }
    public function getValidAccountDate()
    {
        return $this->_u_valid_account_date;
    }
    public function getAccreditation()
    {
        return $this->_u_accreditation;
    }
    #endregion

    #region SETTER
    public function setId($value)
    {
        if(!isset($value) || empty($value) || !$value)
        {
            throw new \InvalidArgumentException('Les informations de l\'id sont incorrectes.' );
        }
        $this->_u_id=$value;
    }
    public function setIdentifiant($value)
    {
        if(!isset($value) || empty($value) || !$value)
        {
            throw new \InvalidArgumentException('Les informations de l\'identifiant sont incorrectes.');
        }
        else if (strlen($value)<=3)
        {
            throw new \InvalidArgumentException('L\'identifiant doit avoir plus de 3 caractères.');
        }
        $this->_u_identifiant=$value;
    }
    public function setMail($value)
    {
        if(!isset($value) || empty($value) || !$value)
        {
            throw new \InvalidArgumentException('Les informations du mail sont incorrectes.');
        }
        $this->_u_mail=$value;
    }
    public function setPassword($value)
    {
        if(!isset($value) || empty($value) || !$value)
        {
            throw new \InvalidArgumentException('Les informations du mot de passe sont incorrectes.');
        }
        else if (strlen($value)!=60)
        {
            throw new \InvalidArgumentException('La longueur du mot de passe est incorrecte. (' . strlen($value) . ' caractères)');
        }
        $this->_u_password=$value;
    }
    public function setForgetHash($value)
    {
        $this->_u_forget_hash=$value;
    }
    public function setForgetDate($value)
    {
        $this->_u_forget_date=$value;
    }
    public function setValidAccountHash($value)
    {
        $this->_u_valid_account_hash=$value;
    }
    public function setValidAccountDate($value)
    {
        $this->_u_valid_account_date=$value;
    }
    public function setAccreditation($value)
    {
        $this->_u_accreditation=$value;
    }
    #endregion
}