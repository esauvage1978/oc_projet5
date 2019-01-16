<?php

namespace ES\App\Modules\User\Model;

use ES\Core\Model\AbstractManager;
use ES\App\Modules\User\Model\UserTable;
Use ES\Core\ToolBox\Auth;
/**
 * UserManager short summary.
 *
 * UserManager description.
 *
 * @version 1.0
 * @author ragus
 */
class UserManager extends AbstractManager
{
    protected static $table='ocp5_user';
    protected static $order_by;
    protected static $id='u_id';

    public function identifiantExist($identifiant)
    {
        return parent::exist('u_identifiant', $identifiant);
    }
    public function mailExist($mail)
    {
        return parent::exist('u_mail', $mail);
    }

    public function findUserByLogin($login)
    {
        if(isset($login))
        {
            $retour= $this->query(
            $this->_selectAll . static::$table . ' WHERE (u_identifiant=:identifiant OR u_mail=:mail) ;'
            , [
            'identifiant'=>$login,
            'mail'=>$login]
            ,
            true);
            if(!$retour)
            {
                throw new \InvalidArgumentException('Le login n\'a pass été trouvé');
            }
            else
            {
             return new UserTable($retour);
            }
        }
        throw new \InvalidArgumentException('Le login est vide');
    }
    public function findByPasswordForget($key)
    {
        if(isset($key))
        {
            $retour= $this->findByField('u_password_forget',$key);
            if(!$retour)
            {
                throw new \InvalidArgumentException('Le compte n\'a pass été trouvé');
            }
            else
            {
                return new UserTable($retour);
            }
        }
        throw new \InvalidArgumentException('L\'adresse est inccorecte');
    }

    public function updateUserForPwdForget(UserTable $user,$initialisation=true):UserTable
    {
        $user->setPassword_forget($initialisation?Auth::str_random():null);
        $user->setPassword_forget_date(date("Y-m-d H:i:s"));

        $this->update($user->getId(),[
            'u_password_forget'=>$user->getPassword_forget(),
            'u_password_forget_date'=>$user->getPassword_forget_date()
            ]);

        return $user;
    }

    public function updatePwd(UserTable $user):UserTable
    {
        $this->update($user->getId(),[
            'u_password'=>$user->getPassword()
            ]);

        return $user;
    }
    public function sendMailSignup($user)
    {

    }
    public function sendMailPwdForget(UserTable $user)
    {
        $content='<a href="' . ES_ROOT_PATH_WEB_INDEX . '?page=user.pwdforgetchange&mot=' . $user->getPassword_forget() . '">Lien pour modifier le mot de passe</a>';
        mail($user->getMail(),'Réinitialisation du mot de passe',$content);
    }
}