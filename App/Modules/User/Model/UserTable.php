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


    private $u_id;
    private $u_identifiant;
    private $u_mail;
    private $u_password;


    #region CHECK DONNEES
    public function getIdentifiant():string
    {
        return $this->u_identifiant;
    }
    public function getMail():string
    {
        return $this->u_mail;
    }
    public function getPassword():string
    {
        return $this->u_password;
    }
    public function setIdentifiant($value)
    {
        if(!isset($value) || empty($value) || !$value)
            throw new \InvalidArgumentException('Les informations de l\'identifiant sont incorrectes.');
        else if (strlen($value)<=3)
            throw new \InvalidArgumentException('L\'identifiant doit avoir plus de 3 caractères.');
        $this->u_identifiant=$value;
    }

    public function setMail($value)
    {
        if(!isset($value) || empty($value) || !$value)
            throw new \InvalidArgumentException('Les informations du mail sont incorrectes.');
        $this->u_mail=$value;
    }

    public function setPassword($value)
    {
        if(!isset($value) || empty($value) || !$value)
            throw new \InvalidArgumentException('Les informations du mot de passe sont incorrectes.');
        else if (strlen($value)!=60)
            throw new \InvalidArgumentException('La longueur du mot de passe est incorrecte. (' . strlen($value) . ' caractères)');
        $this->u_password=$value;
    }
    public function setId($value)
    {
        if(!isset($value) || empty($value) || !$value)
            throw new \InvalidArgumentException('Les informations de l\'id sont incorrectes.');
        else if (!is_int($value))
        $this->u_id=$value;
    }
    #endregion
}